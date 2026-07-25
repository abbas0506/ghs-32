<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Each installment is one payment received against a parent grant.
     * Session scoping is done by checking received_date falls within session start_date–end_date.
     */
    public function up(): void
    {
        Schema::create('grant_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grant_id')->constrained('grants')->cascadeOnDelete();
            $table->unsignedInteger('amount');
            $table->date('received_date');
            $table->string('description')->nullable();
            $table->string('cheque_no', 50)->nullable();
            $table->foreignId('transaction_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grant_installments');
    }
};
