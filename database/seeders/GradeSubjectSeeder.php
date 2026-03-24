<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\GradeSubject;
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
        GradeSubject::create(['grade_id' => 0, 'subject_id' => 3]);
        GradeSubject::create(['grade_id' => 0, 'subject_id' => 4]);
        GradeSubject::create(['grade_id' => 0, 'subject_id' => 5]);

        GradeSubject::create(['grade_id' => 1, 'subject_id' => 3]);
        GradeSubject::create(['grade_id' => 1, 'subject_id' => 4]);
        GradeSubject::create(['grade_id' => 1, 'subject_id' => 7]);
        GradeSubject::create(['grade_id' => 1, 'subject_id' => 2]);
        GradeSubject::create(['grade_id' => 1, 'subject_id' => 21]);
        GradeSubject::create(['grade_id' => 1, 'subject_id' => 22]);
    }
}
