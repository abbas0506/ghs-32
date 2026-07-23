<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * A special grant is a top-level record (e.g. "Govt Computer Lab Grant").
     * Actual money received is tracked in special_grant_installments.
     */
    public function up(): void
    {
        Schema::create('special_grants', function (Blueprint $table) {
            $table->id();
            $table->string('title');                      // Grant name / title
            $table->string('issued_by')->nullable();      // Issuing authority / department
            $table->string('description')->nullable();    // Notes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('special_grants');
    }
};
