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
        Schema::create('lesson_plan_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_plan_id')->constrained()->cascadeOnDelete();
            $table->string('resource_type')->enum(['video', 'document', 'link']); // e.g., 'video', 'document', 'link'
            $table->string('resource_url');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_plan_resources');
    }
};
