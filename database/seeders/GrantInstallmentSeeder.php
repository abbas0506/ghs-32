<?php

namespace Database\Seeders;

use App\Models\Grant;
use App\Models\GrantInstallment;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GrantInstallmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seed grant installments/receipts and auto-post double entry transactions to SMC Bank Account (Code: 1007).
     */
    public function run(): void
    {
        $smcBankAcc = Account::where('code', '1007')->orWhere('name', 'like', '%SMC%')->first();
        $grantIncomeAcc = Account::where('code', '4002')->orWhere('name', 'like', '%Grant%')->first();

        $nsbGrant = Grant::where('title', 'like', '%NSB%')->orWhere('title', 'like', '%Non-Salary%')->first();
        $labGrant = Grant::where('title', 'like', '%Computer%')->first();
        $sportsGrant = Grant::where('title', 'like', '%Sports%')->first();

        $installmentsData = [];

        if ($nsbGrant) {
            $installmentsData[] = [
                'grant' => $nsbGrant,
                'amount' => 45000,
                'date' => Carbon::parse('2026-04-15'),
                'desc' => 'First Quarter NSB Fund Receipt',
                'chq' => 'CHQ-NSB-001'
            ];
            $installmentsData[] = [
                'grant' => $nsbGrant,
                'amount' => 38000,
                'date' => Carbon::parse('2026-07-10'),
                'desc' => 'Second Quarter NSB Fund Receipt',
                'chq' => 'CHQ-NSB-002'
            ];
        }

        if ($labGrant) {
            $installmentsData[] = [
                'grant' => $labGrant,
                'amount' => 80000,
                'date' => Carbon::parse('2026-05-20'),
                'desc' => 'First installment — hardware purchase',
                'chq' => 'CHQ-LAB-001'
            ];
            $installmentsData[] = [
                'grant' => $labGrant,
                'amount' => 40000,
                'date' => Carbon::parse('2026-08-10'),
                'desc' => 'Second installment — software licenses',
                'chq' => 'CHQ-LAB-002'
            ];
        }

        if ($sportsGrant) {
            $installmentsData[] = [
                'grant' => $sportsGrant,
                'amount' => 35000,
                'date' => Carbon::parse('2026-08-15'),
                'desc' => 'Full grant received in one installment',
                'chq' => 'CHQ-SPT-001'
            ];
        }

        foreach ($installmentsData as $inst) {
            DB::transaction(function () use ($inst, $smcBankAcc, $grantIncomeAcc) {
                $txn = null;
                if ($smcBankAcc && $grantIncomeAcc) {
                    $txn = Transaction::create([
                        'date'        => $inst['date'],
                        'cheque_no'   => $inst['chq'],
                        'description' => 'Grant Receipt: ' . $inst['grant']->title . ' (' . $inst['desc'] . ')',
                        'created_at'  => $inst['date'],
                        'updated_at'  => $inst['date'],
                    ]);

                    // Dr SMC Bank Account (Asset Inflow)
                    $txn->lines()->create([
                        'account_id' => $smcBankAcc->id,
                        'debit'      => $inst['amount'],
                        'credit'     => 0,
                        'created_at' => $inst['date'],
                        'updated_at' => $inst['date'],
                    ]);

                    // Cr Grant Receipts Income (Income)
                    $txn->lines()->create([
                        'account_id' => $grantIncomeAcc->id,
                        'debit'      => 0,
                        'credit'     => $inst['amount'],
                        'created_at' => $inst['date'],
                        'updated_at' => $inst['date'],
                    ]);
                }

                GrantInstallment::firstOrCreate(
                    [
                        'grant_id'      => $inst['grant']->id,
                        'received_date' => $inst['date'],
                    ],
                    [
                        'amount'         => $inst['amount'],
                        'description'    => $inst['desc'],
                        'cheque_no'      => $inst['chq'],
                        'transaction_id' => $txn ? $txn->id : null,
                    ]
                );
            });
        }
    }
}
