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
        // 1. Non-Salary Budget (NSB) Grant
        Grant::firstOrCreate(
            ['title' => 'Non-Salary Budget (NSB)'],
            [
                'issued_by'   => 'Government of Punjab',
                'description' => 'Annual non-salary budget for school operations',
            ]
        );

    }
}
