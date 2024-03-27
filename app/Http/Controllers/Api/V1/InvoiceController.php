<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Http\Resources\V1\InvoiceResource;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{

    public function index()
    {
        return InvoiceResource::collection(Invoice::with('user')->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required|max:1',
            'paid' => 'required|numeric|between:0,1',
            'payment_date' => 'nullable',
            'value' => 'required|numeric|between:1,9999.99'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $created = Invoice::create($validator->validated());

        if (!$created) {
            return response()->json(['message' => 'Erro ao criar a fatura.'], 422);
        }
    
        return response()->json(['message' => 'Fatura criada com sucesso.'], 200);

    }

    public function show(Invoice $invoice)
    {
        return new InvoiceResource($invoice);
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required|max:1|in:' . implode(',', ['b', 'c', 'p']),
            'paid' => 'required|numeric|between:0,1',
            'payment_date' => 'nullable|date_format:Y-m-d H:i:s',
            'value' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $validated = $validator->validated();
        
        $updated = $invoice->update([
            'user_id' => $validated['user_id'],
            'type' => $validated['type'],
            'paid' => $validated['paid'],
            'value' => $validated['value'],
            'payment_date' => $validated['paid'] ? $validated['payment_date'] : null
        ]);

        if (!$updated) {
            return response()->json(['message' => 'Erro ao atualizar o pagamento.'], 400);
        }
        return response()->json(['message' => 'Pagamento atualizado com sucesso.'], 200);

    }

    public function destroy(Invoice $invoice)
    {
        $deleted = $invoice->delete();

        if(!$deleted) {
            return response()->json(['message' => 'Erro ao deletar o pagamento.'], 400);
        }

        return response()->json(['message' => 'Pagamento deletado com sucesso.'], 200);
    }
}
