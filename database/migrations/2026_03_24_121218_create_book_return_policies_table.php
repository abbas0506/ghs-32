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
        Schema::create('book_return_policies', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('max_days')->default(7);
            $table->unsignedTinyInteger('fine_per_day')->default(10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_return_policies');
    }
};
