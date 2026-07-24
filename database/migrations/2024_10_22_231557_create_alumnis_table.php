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
        Schema::create('alumnis', function (Blueprint $table) {
            $table->id();
            $table->string('prefix')->nullable();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->unsignedSmallInteger('session')->nullable();
            $table->text('introduction')->nullable();
            $table->string('photo')->nullable();
            $table->unsignedInteger('display_order')->default(1000);
            $table->boolean('is_approved')->default(false); // merged from add_is_approved_to_alumnis
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumnis');
    }
};
