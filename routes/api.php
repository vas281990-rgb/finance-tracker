<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Middleware\EnsureValidTransactionType;
use Illuminate\Support\Facades\Route;

// Public routes — no token required
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Protected routes — token required (Sanctum middleware)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Transaction routes with extra middleware to validate 'type' filter
    Route::middleware(EnsureValidTransactionType::class)
        ->apiResource('transactions', TransactionController::class);

    // Analytics routes
    Route::get('/analytics/summary', [AnalyticsController::class, 'summary']);
});