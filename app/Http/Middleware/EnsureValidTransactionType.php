<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureValidTransactionType
{
    /**
     * Check that the 'type' query parameter, if provided, is valid.
     * Allowed values: 'income', 'expense'.
     * This protects the query scopes from receiving unexpected values.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedTypes = ['income', 'expense'];

        if ($request->filled('type') && ! in_array($request->type, $allowedTypes)) {
            return response()->json([
                'message' => 'Invalid transaction type. Allowed values: income, expense.',
            ], 422);
        }

        return $next($request);
    }
}