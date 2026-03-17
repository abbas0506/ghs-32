<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradeSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Grade::all()->each(function ($grade) {
            $grade->subjects()->attach([1, 2, 3, 4, 6, 10]); // Attach subjects with IDs 1, 2, and 3 to each grade
        });
    }
}
