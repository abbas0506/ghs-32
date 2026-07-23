<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Each installment is one payment received against a parent special grant.
     * Session scoping is done by checking received_date falls within session start_date–end_date.
     */
    public function up(): void
    {
        Schema::create('special_grant_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('special_grant_id')->constrained('special_grants')->cascadeOnDelete();
            $table->unsignedInteger('amount');
            $table->date('received_date');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('special_grant_installments');
    }
};
