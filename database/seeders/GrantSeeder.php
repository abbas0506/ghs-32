<?php

namespace Database\Seeders;

use App\Models\Grant;
use Illuminate\Database\Seeder;

class GrantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seed grant types/categories only.
     */
    public function run(): void
    {
        // 1. School Management Committee (SMC) Grant
        Grant::firstOrCreate(
            ['title' => 'SMC'],
            [
                'issued_by'   => 'Government of Punjab',
                'description' => 'School Management Committee Grant for operations and management',
            ]
        );

        // 2. Annual Development Program (ADP) Grant
        Grant::firstOrCreate(
            ['title' => 'ADP'],
            [
                'issued_by'   => 'Planning & Development Board',
                'description' => 'Annual Development Program Grant for infrastructure extension',
            ]
        );

        // 3. Early Childhood Care and Education (ECCE) Grant
        Grant::firstOrCreate(
            ['title' => 'ECCE'],
            [
                'issued_by'   => 'School Education Department',
                'description' => 'Early Childhood Care and Education Classrooms Grant',
            ]
        );
    }
}
