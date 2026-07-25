<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Session scoping is done by checking created_at falls within a session's start_date–end_date.
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

            $table->unsignedInteger('amount'); // Gross amount
            $table->string('tax_type', 15)->default('none'); // 'none', 'purchase', 'service'
            $table->decimal('gst_rate', 5, 2)->default(0.00);
            $table->decimal('pst_rate', 5, 2)->default(0.00);
            $table->decimal('it_rate', 5, 2)->default(0.00);
            $table->unsignedInteger('net_amount');
            $table->boolean('status')->default(false);
            $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();
            $table->string('fund_type', 15)->default('nsb'); // 'ftf', 'nsb', or 'grant'
            $table->foreignId('grant_id')->nullable()->constrained('grants')->nullOnDelete();
            $table->string('receipt_no', 50);
            $table->string('expense_type', 20)->default('purchase'); // 'purchase', 'service', 'utility', 'other'
            $table->foreignId('school_resolution_id')->nullable()->constrained('school_resolutions')->nullOnDelete();
            $table->string('description', 255)->nullable();

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
