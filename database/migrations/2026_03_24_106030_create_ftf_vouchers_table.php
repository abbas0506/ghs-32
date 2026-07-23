<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Session scoping is done by checking due_date falls within a session's start_date–end_date.
     */
    public function up(): void
    {
        Schema::create('ftf_vouchers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('year');
            $table->unsignedTinyInteger('month');
            $table->unsignedInteger('amount')->nullable();
            $table->date('due_date');
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ftf_vouchers');
    }
};
