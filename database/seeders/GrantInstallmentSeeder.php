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
        $smcBankAcc = Account::where('code', '1007')->first();
        $grantIncomeAcc = Account::where('code', '4002')->orWhere('name', 'like', '%Grant%')->first();

        $smcGrant = Grant::where('title', 'SMC')->first();
        $adpGrant = Grant::where('title', 'ADP')->first();
        $ecceGrant = Grant::where('title', 'ECCE')->first();

        $installmentsData = [];

        if ($smcGrant) {
            $installmentsData[] = [
                'grant' => $smcGrant,
                'amount' => 150000,
                'date' => Carbon::parse('2026-04-10'),
                'desc' => 'First Quarter SMC Fund Receipt',
                'chq' => 'CHQ-SMC-RCV01'
            ];
            $installmentsData[] = [
                'grant' => $smcGrant,
                'amount' => 120000,
                'date' => Carbon::parse('2026-07-15'),
                'desc' => 'Second Quarter SMC Fund Receipt',
                'chq' => 'CHQ-SMC-RCV02'
            ];
        }

        if ($adpGrant) {
            $installmentsData[] = [
                'grant' => $adpGrant,
                'amount' => 300000,
                'date' => Carbon::parse('2026-05-05'),
                'desc' => 'First installment — building extension',
                'chq' => 'CHQ-ADP-RCV01'
            ];
            $installmentsData[] = [
                'grant' => $adpGrant,
                'amount' => 250000,
                'date' => Carbon::parse('2026-08-10'),
                'desc' => 'Second installment — building extension',
                'chq' => 'CHQ-ADP-RCV02'
            ];
        }

        if ($ecceGrant) {
            $installmentsData[] = [
                'grant' => $ecceGrant,
                'amount' => 100000,
                'date' => Carbon::parse('2026-06-01'),
                'desc' => 'ECCE classroom setup grant',
                'chq' => 'CHQ-ECE-RCV01'
            ];
        }

        foreach ($installmentsData as $inst) {
            DB::transaction(function () use ($inst, $smcBankAcc, $grantIncomeAcc) {
                // 1. Create the Grant Installment record
                $installment = GrantInstallment::create([
                    'grant_id'      => $inst['grant']->id,
                    'amount'        => $inst['amount'],
                    'received_date' => $inst['date'],
                    'description'   => $inst['desc'],
                ]);

                // 2. Post double entry transaction
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
            });
        }
    }
}
