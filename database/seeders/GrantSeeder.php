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

        // 2. Computer Lab Upgrades Grant
        Grant::firstOrCreate(
            ['title' => 'Computer Lab Upgrades Grant'],
            [
                'issued_by'   => 'Punjab Education Foundation',
                'description' => 'Grant for computer lab hardware and software upgrades',
            ]
        );

        // 3. Sports Gala Equipment Grant
        Grant::firstOrCreate(
            ['title' => 'Sports Gala Equipment Grant'],
            [
                'issued_by'   => 'District Education Office',
                'description' => 'Grant for annual sports gala equipment and prizes',
            ]
        );
    }
}
