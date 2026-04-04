<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
{
    /**
     * Only authenticated users can update transactions.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for updating a transaction.
     * All fields are optional (sometimes) — only validate what is sent.
     */
    public function rules(): array
    {
        return [
            'type'             => 'sometimes|in:income,expense',
            'amount'           => 'sometimes|numeric|min:0.01',
            'description'      => 'sometimes|string|max:255',
            'category'         => 'nullable|string|max:100',
            'transaction_date' => 'sometimes|date',
        ];
    }
}