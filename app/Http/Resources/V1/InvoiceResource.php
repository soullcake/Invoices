<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class InvoiceResource extends JsonResource
{
    private array $types = ['b' => 'Boleto', 'c' => 'Cartao', 'p' => 'Pix'];


    public function toArray(Request $request): array
    {
        $paid = $this->paid;
        return [
            'user' => [
                 'username' => $this->user->name,
                 'UserEmail' => $this->user->email,
            ],
            'type' => $this->types[$this->type],
            'value' =>'R$ ' . number_format($this->value, 2, ',','.'),
            'paid' => $paid ? 'Pago' : 'Nao Pago',
            'paymentDate' => $paid ? Carbon::parse($this->payment_date)->format('d/m/y H:i:s') : NULL,
            'paidSince' => $paid ? Carbon::parse($this->payment_date)->diffForHumans() : NULL,
        ];
    }
}
