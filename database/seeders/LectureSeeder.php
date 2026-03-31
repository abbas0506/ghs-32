<?php

namespace Database\Seeders;

use App\Models\LectureTiming;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LectureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        LectureTiming::create([
            'lecture_no' => 1,
            'starts_at' => '09:00:00'
        ]);
        LectureTiming::create([
            'lecture_no' => 2,
            'starts_at' => '09:30:00'
        ]);
        LectureTiming::create([
            'lecture_no' => 3,
            'starts_at' => '10:00:00'
        ]);
        LectureTiming::create([
            'lecture_no' => 4,
            'starts_at' => '10:30:00'
        ]);
        LectureTiming::create([
            'lecture_no' => 5,
            'starts_at' => '11:00:00'
        ]);
        LectureTiming::create([
            'lecture_no' => 6,
            'starts_at' => '11:30:00'
        ]);
        LectureTiming::create([
            'lecture_no' => 7,
            'starts_at' => '12:30:00'
        ]);
        LectureTiming::create([
            'lecture_no' => 8,
            'starts_at' => '1:00:00'
        ]);
    }
}
