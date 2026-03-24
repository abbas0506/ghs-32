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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            // staff_id: user_id
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('month');
            $table->unsignedMediumInteger('year');
            $table->unsignedMediumInteger('amount')->default(0);
            $table->boolean('status')->default(false);
            $table->unique(['user_id', 'month', 'year']);
            $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();

            // Apply the composite unique index
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salaries', function (Blueprint $table) {
            // To drop the index, use the same array syntax
            $table->dropUnique(['user_id', 'month', 'year']);
        });
        Schema::dropIfExists('salaries');
    }
};
