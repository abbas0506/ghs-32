<?php

namespace Database\Seeders;

use App\Models\SpecialGrant;
use App\Models\SpecialGrantInstallment;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SpecialGrantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeds two grants with their installments.
     * Session scoping is handled via received_date on installments.
     */
    public function run(): void
    {
        // Grant 1: Computer Lab Upgrades (2 installments)
        $labGrant = SpecialGrant::firstOrCreate(
            ['title' => 'Computer Lab Upgrades Grant'],
            [
                'issued_by'   => 'Punjab Education Foundation',
                'description' => 'Grant for computer lab hardware and software upgrades',
            ]
        );

        SpecialGrantInstallment::firstOrCreate(
            ['special_grant_id' => $labGrant->id, 'received_date' => Carbon::parse('2026-05-20')],
            ['amount' => 80000, 'description' => 'First installment — hardware purchase']
        );

        SpecialGrantInstallment::firstOrCreate(
            ['special_grant_id' => $labGrant->id, 'received_date' => Carbon::parse('2026-08-10')],
            ['amount' => 40000, 'description' => 'Second installment — software licenses']
        );

        // Grant 2: Sports Gala Equipment (1 installment)
        $sportsGrant = SpecialGrant::firstOrCreate(
            ['title' => 'Sports Gala Equipment Grant'],
            [
                'issued_by'   => 'District Education Office',
                'description' => 'Grant for annual sports gala equipment and prizes',
            ]
        );

        SpecialGrantInstallment::firstOrCreate(
            ['special_grant_id' => $sportsGrant->id, 'received_date' => Carbon::parse('2026-08-15')],
            ['amount' => 35000, 'description' => 'Full grant received in one installment']
        );
    }
}
