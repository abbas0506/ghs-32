<?php

namespace Database\Seeders;

use App\Models\NsbReceipt;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class NsbReceiptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Session scoping is handled via received_date falling within the session's date range.
     */
    public function run(): void
    {
        // Q1 receipt
        NsbReceipt::firstOrCreate(
            ['quarter' => 1, 'received_date' => Carbon::parse('2026-04-15')],
            [
                'amount'      => 45000,
                'description' => 'First Quarter NSB Fund Receipt',
            ]
        );

        // Q2 receipt
        NsbReceipt::firstOrCreate(
            ['quarter' => 2, 'received_date' => Carbon::parse('2026-07-10')],
            [
                'amount'      => 38000,
                'description' => 'Second Quarter NSB Fund Receipt',
            ]
        );
    }
}
