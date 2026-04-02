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
        Schema::table('lecture_timings', function (Blueprint $table) {
            // add duratio column
            $table->integer('duration')->nullable()->default(30)->after('starts_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lecture_timings', function (Blueprint $table) {
            //
            $table->dropColumn('duration');
        });
    }
};
