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

        //Nursery
        GradeSubject::create(['grade_id' => 1, 'subject_id' => 3]);
        GradeSubject::create(['grade_id' => 1, 'subject_id' => 4]);
        GradeSubject::create(['grade_id' => 1, 'subject_id' => 5]);

        // class 1
        GradeSubject::create(['grade_id' => 2, 'subject_id' => 3]);
        GradeSubject::create(['grade_id' => 2, 'subject_id' => 4]);
        GradeSubject::create(['grade_id' => 2, 'subject_id' => 7]);
        GradeSubject::create(['grade_id' => 2, 'subject_id' => 2]);
        GradeSubject::create(['grade_id' => 2, 'subject_id' => 21]);
        GradeSubject::create(['grade_id' => 2, 'subject_id' => 22]);
        // class 2
        GradeSubject::create(['grade_id' => 3, 'subject_id' => 2]);
        GradeSubject::create(['grade_id' => 3, 'subject_id' => 3]);
        GradeSubject::create(['grade_id' => 3, 'subject_id' => 4]);
        GradeSubject::create(['grade_id' => 3, 'subject_id' => 21]);
        GradeSubject::create(['grade_id' => 3, 'subject_id' => 22]);
        GradeSubject::create(['grade_id' => 3, 'subject_id' => 23]);
        // class 3
        GradeSubject::create(['grade_id' => 4, 'subject_id' => 2]);
        GradeSubject::create(['grade_id' => 4, 'subject_id' => 3]);
        GradeSubject::create(['grade_id' => 4, 'subject_id' => 4]);
        GradeSubject::create(['grade_id' => 4, 'subject_id' => 7]);
        GradeSubject::create(['grade_id' => 4, 'subject_id' => 21]);
        GradeSubject::create(['grade_id' => 4, 'subject_id' => 22]);
        GradeSubject::create(['grade_id' => 4, 'subject_id' => 23]);

        // class 4
        GradeSubject::create(['grade_id' => 5, 'subject_id' => 2]);
        GradeSubject::create(['grade_id' => 5, 'subject_id' => 3]);
        GradeSubject::create(['grade_id' => 5, 'subject_id' => 4]);
        GradeSubject::create(['grade_id' => 5, 'subject_id' => 7]);
        GradeSubject::create(['grade_id' => 5, 'subject_id' => 16]);
        GradeSubject::create(['grade_id' => 5, 'subject_id' => 20]);
        GradeSubject::create(['grade_id' => 5, 'subject_id' => 21]);
        GradeSubject::create(['grade_id' => 5, 'subject_id' => 23]);
        // class 5
        GradeSubject::create(['grade_id' => 6, 'subject_id' => 2]);
        GradeSubject::create(['grade_id' => 6, 'subject_id' => 3]);
        GradeSubject::create(['grade_id' => 6, 'subject_id' => 4]);
        GradeSubject::create(['grade_id' => 6, 'subject_id' => 7]);
        GradeSubject::create(['grade_id' => 6, 'subject_id' => 16]);
        GradeSubject::create(['grade_id' => 6, 'subject_id' => 20]);
        GradeSubject::create(['grade_id' => 6, 'subject_id' => 21]);
        GradeSubject::create(['grade_id' => 6, 'subject_id' => 23]);
        // class 6
        GradeSubject::create(['grade_id' => 7, 'subject_id' => 1]);
        GradeSubject::create(['grade_id' => 7, 'subject_id' => 2]);
        GradeSubject::create(['grade_id' => 7, 'subject_id' => 3]);
        GradeSubject::create(['grade_id' => 7, 'subject_id' => 4]);
        GradeSubject::create(['grade_id' => 7, 'subject_id' => 7]);
        GradeSubject::create(['grade_id' => 7, 'subject_id' => 13]);
        GradeSubject::create(['grade_id' => 7, 'subject_id' => 14]);
        GradeSubject::create(['grade_id' => 7, 'subject_id' => 16]);
        GradeSubject::create(['grade_id' => 7, 'subject_id' => 19]);
        // class 7
        GradeSubject::create(['grade_id' => 8, 'subject_id' => 1]);
        GradeSubject::create(['grade_id' => 8, 'subject_id' => 2]);
        GradeSubject::create(['grade_id' => 8, 'subject_id' => 3]);
        GradeSubject::create(['grade_id' => 8, 'subject_id' => 4]);
        GradeSubject::create(['grade_id' => 8, 'subject_id' => 7]);
        GradeSubject::create(['grade_id' => 8, 'subject_id' => 13]);
        GradeSubject::create(['grade_id' => 8, 'subject_id' => 14]);
        GradeSubject::create(['grade_id' => 8, 'subject_id' => 16]);
        GradeSubject::create(['grade_id' => 8, 'subject_id' => 19]);

        // class 8
        GradeSubject::create(['grade_id' => 9, 'subject_id' => 1]);
        GradeSubject::create(['grade_id' => 9, 'subject_id' => 2]);
        GradeSubject::create(['grade_id' => 9, 'subject_id' => 3]);
        GradeSubject::create(['grade_id' => 9, 'subject_id' => 4]);
        GradeSubject::create(['grade_id' => 9, 'subject_id' => 7]);
        GradeSubject::create(['grade_id' => 9, 'subject_id' => 13]);
        GradeSubject::create(['grade_id' => 9, 'subject_id' => 14]);
        GradeSubject::create(['grade_id' => 9, 'subject_id' => 16]);
        GradeSubject::create(['grade_id' => 9, 'subject_id' => 19]);

        // class 9
        GradeSubject::create(['grade_id' => 10, 'subject_id' => 1]);
        GradeSubject::create(['grade_id' => 10, 'subject_id' => 2]);
        GradeSubject::create(['grade_id' => 10, 'subject_id' => 3]);
        GradeSubject::create(['grade_id' => 10, 'subject_id' => 4]);
        GradeSubject::create(['grade_id' => 10, 'subject_id' => 5]);
        GradeSubject::create(['grade_id' => 10, 'subject_id' => 6]);
        GradeSubject::create(['grade_id' => 10, 'subject_id' => 7]);
        GradeSubject::create(['grade_id' => 10, 'subject_id' => 8]);
        GradeSubject::create(['grade_id' => 10, 'subject_id' => 9]);
        GradeSubject::create(['grade_id' => 10, 'subject_id' => 10]);
        GradeSubject::create(['grade_id' => 10, 'subject_id' => 11]);
        GradeSubject::create(['grade_id' => 10, 'subject_id' => 12]);
        GradeSubject::create(['grade_id' => 10, 'subject_id' => 13]);
        GradeSubject::create(['grade_id' => 10, 'subject_id' => 14]);
        GradeSubject::create(['grade_id' => 10, 'subject_id' => 15]);
        GradeSubject::create(['grade_id' => 10, 'subject_id' => 16]);
        GradeSubject::create(['grade_id' => 10, 'subject_id' => 18]);

        // class 10
        GradeSubject::create(['grade_id' => 11, 'subject_id' => 1]);
        GradeSubject::create(['grade_id' => 11, 'subject_id' => 2]);
        GradeSubject::create(['grade_id' => 11, 'subject_id' => 3]);
        GradeSubject::create(['grade_id' => 11, 'subject_id' => 4]);
        GradeSubject::create(['grade_id' => 11, 'subject_id' => 5]);
        GradeSubject::create(['grade_id' => 11, 'subject_id' => 6]);
        GradeSubject::create(['grade_id' => 11, 'subject_id' => 7]);
        GradeSubject::create(['grade_id' => 11, 'subject_id' => 8]);
        GradeSubject::create(['grade_id' => 11, 'subject_id' => 9]);
        GradeSubject::create(['grade_id' => 11, 'subject_id' => 10]);
        GradeSubject::create(['grade_id' => 11, 'subject_id' => 11]);
        GradeSubject::create(['grade_id' => 11, 'subject_id' => 12]);
        GradeSubject::create(['grade_id' => 11, 'subject_id' => 13]);
        GradeSubject::create(['grade_id' => 11, 'subject_id' => 14]);
        GradeSubject::create(['grade_id' => 11, 'subject_id' => 15]);
        GradeSubject::create(['grade_id' => 11, 'subject_id' => 16]);
        GradeSubject::create(['grade_id' => 11, 'subject_id' => 18]);
    }
}
