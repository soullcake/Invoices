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

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        return new InvoiceResource($invoice);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}