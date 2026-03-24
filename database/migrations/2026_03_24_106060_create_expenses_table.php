<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            // Expense type (Electricity, Rent, etc.)
            $table->foreignId('expense_account_id')
                ->constrained('accounts');

            // Always Cash (asset account)
            $table->foreignId('payment_account_id')
                ->constrained('accounts');

            $table->unsignedInteger('amount');
            $table->boolean('status')->default(false);
            $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
