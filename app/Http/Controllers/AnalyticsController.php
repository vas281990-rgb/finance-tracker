<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    /**
     * Return a financial summary for the authenticated user.
     * Supports optional filtering by date range.
     */
    public function summary(Request $request)
    {
        $request->validate([
            'from' => 'nullable|date',
            'to'   => 'nullable|date|after_or_equal:from',
        ]);

        $query = $request->user()->transactions();

        // Apply optional date range filter
        if ($request->filled('from') || $request->filled('to')) {
            $query->inDateRange($request->from, $request->to);
        }

        $transactions = $query->get();

        // Calculate totals using query scopes on the collection
        $totalIncome  = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $balance      = $totalIncome - $totalExpense;

        // Group transactions by category and sum amounts
        $byCategory = $transactions
            ->groupBy('category')
            ->map(fn($group) => [
                'total'       => round($group->sum('amount'), 2),
                'count'       => $group->count(),
            ]);

        // Group by month to show spending trends
        $byMonth = $transactions
            ->groupBy(fn($t) => $t->transaction_date->format('Y-m'))
            ->map(fn($group) => [
                'income'  => round($group->where('type', 'income')->sum('amount'), 2),
                'expense' => round($group->where('type', 'expense')->sum('amount'), 2),
            ]);

        return response()->json([
            'period' => [
                'from' => $request->from ?? 'all time',
                'to'   => $request->to   ?? 'all time',
            ],
            'summary' => [
                'total_income'  => round($totalIncome, 2),
                'total_expense' => round($totalExpense, 2),
                'balance'       => round($balance, 2),
            ],
            'by_category' => $byCategory,
            'by_month'    => $byMonth,
        ]);
    }
}