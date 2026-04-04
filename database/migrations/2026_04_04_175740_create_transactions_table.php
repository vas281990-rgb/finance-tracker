<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the transactions table with all required fields.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // Foreign key linking transaction to a user
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Type of transaction: 'income' or 'expense'
            $table->enum('type', ['income', 'expense']);

            // Amount with 2 decimal places (e.g. 1500.00)
            $table->decimal('amount', 10, 2);

            // Short description of the transaction
            $table->string('description');

            // Optional category (e.g. 'food', 'salary', 'rent')
            $table->string('category')->nullable();

            // Date when the transaction occurred
            $table->date('transaction_date');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migration — drop the table.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};