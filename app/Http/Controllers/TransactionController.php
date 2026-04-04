<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Return a list of all transactions for the authenticated user.
     * Supports optional filtering by type, date range, and category.
     */
    public function index(Request $request)
    {
        $query = $request->user()->transactions();

        // Filter by type: income or expense
        if ($request->filled('type')) {
            $query->ofType($request->type);
        }

        // Filter by date range
        if ($request->filled('from') || $request->filled('to')) {
            $query->inDateRange($request->from, $request->to);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        return response()->json(
            $query->orderBy('transaction_date', 'desc')->get()
        );
    }

    /**
     * Store a new transaction for the authenticated user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'             => 'required|in:income,expense',
            'amount'           => 'required|numeric|min:0.01',
            'description'      => 'required|string|max:255',
            'category'         => 'nullable|string|max:100',
            'transaction_date' => 'required|date',
        ]);

        // Attach transaction to the currently authenticated user
        $transaction = $request->user()->transactions()->create($validated);

        return response()->json($transaction, 201);
    }

    /**
     * Return a single transaction (only if it belongs to the authenticated user).
     */
    public function show(Request $request, Transaction $transaction)
    {
        // Ensure the transaction belongs to the current user
        if ($transaction->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json($transaction);
    }

    /**
     * Update an existing transaction.
     */
    public function update(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'type'             => 'sometimes|in:income,expense',
            'amount'           => 'sometimes|numeric|min:0.01',
            'description'      => 'sometimes|string|max:255',
            'category'         => 'nullable|string|max:100',
            'transaction_date' => 'sometimes|date',
        ]);

        $transaction->update($validated);

        return response()->json($transaction);
    }

    /**
     * Delete a transaction.
     */
    public function destroy(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $transaction->delete();

        return response()->json(['message' => 'Transaction deleted successfully']);
    }
}