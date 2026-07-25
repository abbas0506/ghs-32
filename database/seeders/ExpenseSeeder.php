<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Expense;
use App\Models\Transaction;
use App\Models\AcademicSession;
use App\Models\Grant;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        // Get Accounts
        $cashAcc = Account::where('code', '1001')->first();
        $smcBankAcc = Account::where('code', '1007')->first();
        $ftfBankAcc = Account::where('code', '1002')->first();
        $taxWithheldAcc = Account::where('code', '2003')->first();

        // Expense Accounts
        $examAcc  = Account::where('name', 'Exams')->first();
        $labAcc   = Account::where('name', 'Computer Lab')->first();
        $maintAcc = Account::where('name', 'Maintenance')->first();

        // Grants
        $smcGrant = Grant::where('title', 'SMC')->first();
        $adpGrant = Grant::where('title', 'ADP')->first();

        // 1. Seed Cheque Withdrawals (Bank Accounts -> Cash Account)
        $withdrawals = [
            // SMC
            [
                'bank' => $smcBankAcc,
                'grant' => $smcGrant,
                'amount' => 80000,
                'date' => Carbon::parse('2026-04-20'),
                'chq' => 'CHQ-SMC-901',
                'desc' => 'Cheque withdrawal for SMC operational expenses'
            ],
            [
                'bank' => $smcBankAcc,
                'grant' => $smcGrant,
                'amount' => 70000,
                'date' => Carbon::parse('2026-07-20'),
                'chq' => 'CHQ-SMC-902',
                'desc' => 'Cheque withdrawal for SMC operational expenses (Q2)'
            ],
            // ADP
            [
                'bank' => $smcBankAcc,
                'grant' => $adpGrant,
                'amount' => 150000,
                'date' => Carbon::parse('2026-05-15'),
                'chq' => 'CHQ-ADP-911',
                'desc' => 'Cheque withdrawal for ADP laboratory extension'
            ],
            [
                'bank' => $smcBankAcc,
                'grant' => $adpGrant,
                'amount' => 100000,
                'date' => Carbon::parse('2026-08-15'),
                'chq' => 'CHQ-ADP-912',
                'desc' => 'Cheque withdrawal for ADP hardware setup'
            ],
            // FTF
            [
                'bank' => $ftfBankAcc,
                'grant' => null,
                'amount' => 40000,
                'date' => Carbon::parse('2026-04-12'),
                'chq' => 'CHQ-FTF-701',
                'desc' => 'Cheque withdrawal for FTF student-related operations'
            ],
            [
                'bank' => $ftfBankAcc,
                'grant' => null,
                'amount' => 30000,
                'date' => Carbon::parse('2026-07-12'),
                'chq' => 'CHQ-FTF-702',
                'desc' => 'Cheque withdrawal for FTF general repairs'
            ],
        ];

        foreach ($withdrawals as $w) {
            DB::transaction(function () use ($w, $cashAcc) {
                $bankAcc = $w['bank'];
                if ($cashAcc && $bankAcc) {
                    $txn = Transaction::create([
                        'date'        => $w['date'],
                        'cheque_no'   => $w['chq'],
                        'description' => $w['desc'],
                        'grant_id'    => $w['grant'] ? $w['grant']->id : null,
                        'created_at'  => $w['date'],
                        'updated_at'  => $w['date'],
                    ]);

                    // Dr Cash Account (Inflow)
                    $txn->lines()->create([
                        'account_id' => $cashAcc->id,
                        'debit'      => $w['amount'],
                        'credit'     => 0,
                        'created_at' => $w['date'],
                        'updated_at' => $w['date'],
                    ]);

                    // Cr Bank Account (Outflow)
                    $txn->lines()->create([
                        'account_id' => $bankAcc->id,
                        'debit'      => 0,
                        'credit'     => $w['amount'],
                        'created_at' => $w['date'],
                        'updated_at' => $w['date'],
                    ]);
                }
            });
        }

        // 2. Seed Expenses (Always paid via Cash Account)
        $expensesData = [
            // SMC Grant Expenses
            [
                'fund_type' => 'grant',
                'grant' => $smcGrant,
                'account' => $maintAcc,
                'amount' => 20000,
                'date' => Carbon::parse('2026-04-22'),
                'description' => 'Repair school boundary wall',
                'description_detail' => 'Repairing school boundary wall with concrete reinforcement',
                'expense_type' => 'service',
                'tax_type' => 'service',
                'gst' => 0.00,
                'pst' => 20.00,
                'it' => 11.00
            ],
            [
                'fund_type' => 'grant',
                'grant' => $smcGrant,
                'account' => $maintAcc,
                'amount' => 15000,
                'date' => Carbon::parse('2026-04-25'),
                'description' => 'Classroom whiteboard installation',
                'description_detail' => 'Installation of 5 whiteboards in secondary section',
                'expense_type' => 'purchase',
                'tax_type' => 'purchase',
                'gst' => 19.00,
                'pst' => 0.00,
                'it' => 11.00
            ],
            [
                'fund_type' => 'grant',
                'grant' => $smcGrant,
                'account' => $examAcc,
                'amount' => 30000,
                'date' => Carbon::parse('2026-07-22'),
                'description' => 'Purchase of textbooks & stationary',
                'description_detail' => 'Purchase of textbooks & stationary for session 2026-2027',
                'expense_type' => 'purchase',
                'tax_type' => 'purchase',
                'gst' => 19.00,
                'pst' => 0.00,
                'it' => 11.00
            ],
            // ADP Grant Expenses
            [
                'fund_type' => 'grant',
                'grant' => $adpGrant,
                'account' => $labAcc,
                'amount' => 90000,
                'date' => Carbon::parse('2026-05-20'),
                'description' => 'Lab workstation furniture repair',
                'description_detail' => 'Repair and polishing of lab workstation furniture',
                'expense_type' => 'service',
                'tax_type' => 'service',
                'gst' => 0.00,
                'pst' => 20.00,
                'it' => 11.00
            ],
            [
                'fund_type' => 'grant',
                'grant' => $adpGrant,
                'account' => $maintAcc,
                'amount' => 45000,
                'date' => Carbon::parse('2026-05-28'),
                'description' => 'Installation of laboratory sinks',
                'description_detail' => 'Plumbing work and installation of laboratory sinks',
                'expense_type' => 'service',
                'tax_type' => 'service',
                'gst' => 0.00,
                'pst' => 20.00,
                'it' => 11.00
            ],
            [
                'fund_type' => 'grant',
                'grant' => $adpGrant,
                'account' => $labAcc,
                'amount' => 80000,
                'date' => Carbon::parse('2026-08-20'),
                'description' => 'Networking cables for computers',
                'description_detail' => 'Ethernet Cat6 cabling and switches for computer lab',
                'expense_type' => 'purchase',
                'tax_type' => 'purchase',
                'gst' => 19.00,
                'pst' => 0.00,
                'it' => 11.00
            ],
            // FTF Expenses (fund_type = ftf, tax = none)
            [
                'fund_type' => 'ftf',
                'grant' => null,
                'account' => $examAcc,
                'amount' => 15000,
                'date' => Carbon::parse('2026-04-18'),
                'description' => 'Student sports day supplies',
                'description_detail' => 'Purchase of sports materials and printout of rules sheets',
                'expense_type' => 'purchase',
                'tax_type' => 'none',
            ],
            [
                'fund_type' => 'ftf',
                'grant' => null,
                'account' => $maintAcc,
                'amount' => 10000,
                'date' => Carbon::parse('2026-04-26'),
                'description' => 'Computer lab fan replacement',
                'description_detail' => 'Replacement of 2 burnt ceiling fans in computer laboratory',
                'expense_type' => 'service',
                'tax_type' => 'none',
            ],
            [
                'fund_type' => 'ftf',
                'grant' => null,
                'account' => $examAcc,
                'amount' => 20000,
                'date' => Carbon::parse('2026-07-18'),
                'description' => 'Annual prize distribution trophies',
                'description_detail' => 'Purchase of 30 dynamic brass trophies for top students',
                'expense_type' => 'purchase',
                'tax_type' => 'none',
            ],
        ];

        foreach ($expensesData as $item) {
            DB::transaction(function () use ($item, $cashAcc, $taxWithheldAcc) {
                $grossAmount = $item['amount'];
                $taxType = $item['tax_type'] ?? 'none';
                
                $gstRate = $item['gst'] ?? 0.00;
                $pstRate = $item['pst'] ?? 0.00;
                $itRate = $item['it'] ?? 0.00;

                $gstAmount = round(($grossAmount * $gstRate) / 100);
                $pstAmount = round(($grossAmount * $pstRate) / 100);
                $itAmount = round(($grossAmount * $itRate) / 100);
                
                $totalTax = $gstAmount + $pstAmount + $itAmount;
                $netAmount = $grossAmount - $totalTax;

                $grantId = $item['grant'] ? $item['grant']->id : null;
                $fundType = $item['fund_type'] ?? 'grant';

                // 1. Create Transaction
                $transaction = Transaction::create([
                    'date'        => $item['date'],
                    'cheque_no'   => null,
                    'description' => $item['description'] . ' (Paid via Cash)',
                    'created_at'  => $item['date'],
                    'updated_at'  => $item['date'],
                ]);

                // Dr Expense Account (Gross Amount)
                $transaction->lines()->create([
                    'account_id' => $item['account']->id,
                    'debit'      => $grossAmount,
                    'credit'     => 0,
                    'created_at' => $item['date'],
                    'updated_at' => $item['date'],
                ]);

                // Cr Cash Account (Net Amount Paid)
                $transaction->lines()->create([
                    'account_id' => $cashAcc->id,
                    'debit'      => 0,
                    'credit'     => $netAmount,
                    'created_at' => $item['date'],
                    'updated_at' => $item['date'],
                ]);

                // Cr Tax Withheld Liability (Taxes)
                if ($totalTax > 0 && $taxWithheldAcc) {
                    $transaction->lines()->create([
                        'account_id' => $taxWithheldAcc->id,
                        'debit'      => 0,
                        'credit'     => $totalTax,
                        'created_at' => $item['date'],
                        'updated_at' => $item['date'],
                    ]);
                }

                // 2. Create Expense
                Expense::create([
                    'amount'               => $grossAmount,
                    'expense_account_id'   => $item['account']->id,
                    'payment_account_id'   => $cashAcc->id,
                    'status'               => true,
                    'transaction_id'       => $transaction->id,
                    'fund_type'            => $fundType,
                    'grant_id'             => $grantId,
                    'receipt_no'           => 'REC-' . rand(1000, 9999),
                    'tax_type'             => $taxType,
                    'gst_rate'             => $gstRate,
                    'pst_rate'             => $pstRate,
                    'it_rate'              => $itRate,
                    'net_amount'           => $netAmount,
                    'expense_type'         => $item['expense_type'],
                    'description'          => $item['description_detail'],
                    'created_at'           => $item['date'],
                    'updated_at'           => $item['date'],
                ]);
            });
        }
    }
}
