<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the transaction model into a JSON-friendly array.
     * This controls exactly what fields are exposed in the API response.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'type'             => $this->type,
            'amount'           => (float) $this->amount,
            'description'      => $this->description,
            'category'         => $this->category,
            'transaction_date' => $this->transaction_date->format('Y-m-d'),
            'created_at'       => $this->created_at->toDateTimeString(),
        ];
    }
}