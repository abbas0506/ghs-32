<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Session scoping is done by checking received_date falls within a session's start_date–end_date.
     */
    public function up(): void
    {
        Schema::create('nsb_receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('quarter'); // 1 = Q1, 2 = Q2, 3 = Q3, 4 = Q4
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
        Schema::dropIfExists('nsb_receipts');
    }
};
