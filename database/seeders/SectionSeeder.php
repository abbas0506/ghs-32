<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Section::create(['grade_id' => 0, 'name' => 'KG']);
        Section::create(['grade_id' => 1, 'name' => 'Nursery']);
        Section::create(['grade_id' => 2, 'name' => 'One']);
        Section::create(['grade_id' => 3, 'name' => 'Two']);
        Section::create(['grade_id' => 4, 'name' => 'Three']);
        Section::create(['grade_id' => 5, 'name' => 'Four']);
        Section::create(['grade_id' => 6, 'name' => 'Five']);
        Section::create(['grade_id' => 7, 'name' => 'Six']);
        Section::create(['grade_id' => 8, 'name' => 'Seven']);
        Section::create(['grade_id' => 9, 'name' => 'Eight']);
        Section::create(['grade_id' => 10, 'name' => '9th A']);
        Section::create(['grade_id' => 10, 'name' => '9th B']);
        Section::create(['grade_id' => 11, 'name' => '10th']);
    }
}
