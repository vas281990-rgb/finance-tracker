<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Only authenticated users can create transactions.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for creating a new transaction.
     */
    public function rules(): array
    {
        return [
            'type'             => 'required|in:income,expense',
            'amount'           => 'required|numeric|min:0.01',
            'description'      => 'required|string|max:255',
            'category'         => 'nullable|string|max:100',
            'transaction_date' => 'required|date',
        ];
    }

    /**
     * Custom error messages for validation failures.
     */
    public function messages(): array
    {
        return [
            'type.in'               => 'Type must be either income or expense.',
            'amount.min'            => 'Amount must be greater than zero.',
            'transaction_date.date' => 'Please provide a valid date.',
        ];
    }
}