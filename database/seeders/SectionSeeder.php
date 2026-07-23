<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all grades keyed by their grade_no
        $grades = Grade::all()->keyBy('grade_no');

        // Helper to get grade ID by grade_no
        $getGradeId = function($gradeNo) use ($grades) {
            return $grades->has($gradeNo) ? $grades->get($gradeNo)->id : null;
        };

        // Create sections using looked-up grade IDs to prevent constraint violations
        if ($id = $getGradeId(0)) Section::create(['grade_id' => $id, 'name' => 'KG']);
        if ($id = $getGradeId(0)) Section::create(['grade_id' => $id, 'name' => 'Nursery']);
        if ($id = $getGradeId(1)) Section::create(['grade_id' => $id, 'name' => 'One']);
        if ($id = $getGradeId(2)) Section::create(['grade_id' => $id, 'name' => 'Two']);
        if ($id = $getGradeId(3)) Section::create(['grade_id' => $id, 'name' => 'Three']);
        if ($id = $getGradeId(4)) Section::create(['grade_id' => $id, 'name' => 'Four']);
        if ($id = $getGradeId(5)) Section::create(['grade_id' => $id, 'name' => 'Five']);
        if ($id = $getGradeId(6)) Section::create(['grade_id' => $id, 'name' => 'Six']);
        if ($id = $getGradeId(7)) Section::create(['grade_id' => $id, 'name' => 'Seven']);
        if ($id = $getGradeId(8)) Section::create(['grade_id' => $id, 'name' => 'Eight']);
        if ($id = $getGradeId(9)) Section::create(['grade_id' => $id, 'name' => '9th A']);
        if ($id = $getGradeId(9)) Section::create(['grade_id' => $id, 'name' => '9th B']);
        if ($id = $getGradeId(10)) Section::create(['grade_id' => $id, 'name' => '10th']);
    }
}
