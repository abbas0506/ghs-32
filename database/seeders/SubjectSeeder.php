<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Subject::create(['name' => 'Tarjuma tul Quran', 'short_name' => 'THQ']);
        Subject::create(['name' => 'Islamiat', 'short_name' => 'ISL']);
        Subject::create(['name' => 'English', 'short_name' => 'ENG']);
        Subject::create(['name' => 'Urdu', 'short_name' => 'URD']);
        Subject::create(['name' => 'Pak Studies', 'short_name' => 'PST']);
        Subject::create(['name' => 'Physics', 'short_name' => 'PHY']);
        Subject::create(['name' => 'Mathemtics', 'short_name' => 'MTH']);
        Subject::create(['name' => 'Biology', 'short_name' => 'BIO']);
        Subject::create(['name' => 'Chemistry', 'short_name' => 'CHM']);
        Subject::create(['name' => 'Computer Sc.', 'short_name' => 'CSC']);
        Subject::create(['name' => 'Enterprenureship', 'short_name' => 'ENT']);
        Subject::create(['name' => 'Islamic Studies', 'short_name' => 'IST']);
        Subject::create(['name' => 'Arabic', 'short_name' => 'ARB']);
        Subject::create(['name' => 'Agricutlure', 'short_name' => 'AGR']);
        Subject::create(['name' => 'Punjabi', 'short_name' => 'PJB']);
        Subject::create(['name' => 'General Science', 'short_name' => 'GSC']);
        Subject::create(['name' => 'General Math', 'short_name' => 'GMT']);
        Subject::create(['name' => 'ICT', 'short_name' => 'ICT']);
        Subject::create(['name' => 'History & Geo', 'short_name' => 'HST']);
        Subject::create(['name' => 'Social Studies', 'short_name' => 'SST']);
        Subject::create(['name' => 'Nazra Quran', 'short_name' => 'NZR']);
        Subject::create(['name' => 'General Knowledge', 'short_name' => 'GK']);
    }
}
