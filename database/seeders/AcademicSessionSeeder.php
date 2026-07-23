<?php

namespace Database\Seeders;

use App\Models\AcademicSession;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AcademicSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AcademicSession::firstOrCreate(
            ['name' => '2024-2025'],
            [
                'start_date' => Carbon::parse('2024-04-01'),
                'end_date' => Carbon::parse('2025-03-31'),
                'ftf_start' => 25000,
                'nsb_start' => 100000,
                'special_grants_start' => 15000,
                'is_current' => false,
            ]
        );

        AcademicSession::firstOrCreate(
            ['name' => '2025-2026'],
            [
                'start_date' => Carbon::parse('2025-04-01'),
                'end_date' => Carbon::parse('2026-03-31'),
                'ftf_start' => 35000,
                'nsb_start' => 120000,
                'special_grants_start' => 20000,
                'is_current' => false,
            ]
        );

        // Current active session (contains our April/May 2026 vouchers)
        AcademicSession::firstOrCreate(
            ['name' => '2026-2027'],
            [
                'start_date' => Carbon::parse('2026-04-01'),
                'end_date' => Carbon::parse('2027-03-31'),
                'ftf_start' => 15000,
                'nsb_start' => 142000,
                'special_grants_start' => 50000,
                'is_current' => true,
            ]
        );
    }
}
