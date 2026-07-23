<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Expense;
use App\Models\Transaction;
use App\Models\AcademicSession;
use App\Models\SpecialGrant;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        // Get Accounts
        $cashAcc = Account::where('code', '1001')->first();
        $bankAcc = Account::where('code', '1002')->first();
        $taxWithheldAcc = Account::where('code', '2003')->first();

        // Expense Accounts
        $salaryAcc = Account::where('code', '5001')->first();
        $examAcc = Account::where('code', '5002')->first();
        $rentAcc = Account::where('code', '5003')->first();
        $elecAcc = Account::where('code', '5004')->first();
        $netAcc = Account::where('code', '5005')->first();

        // Special Grants
        $labGrant = SpecialGrant::where('title', 'Computer Lab Upgrades Grant')->first();
        $sportsGrant = SpecialGrant::where('title', 'Sports Gala Equipment Grant')->first();

        $session = AcademicSession::where('name', '2026-2027')->first();
        $baseDate = $session ? $session->start_date : Carbon::parse('2026-04-01');

        // Categories configuration: 5 expenses for each: FTF, NSB, Special Grant
        $config = [
            // FTF Category - Non taxable (0 tax)
            [
                'fund_type' => 'ftf',
                'items' => [
                    ['account' => $salaryAcc, 'amount' => 12000, 'date' => $baseDate->copy()->addDays(5), 'description' => 'Security Guard Salary (FTF)', 'payment' => $cashAcc],
                    ['account' => $rentAcc, 'amount' => 8000, 'date' => $baseDate->copy()->addDays(15), 'description' => 'Office Rent portion (FTF)', 'payment' => $bankAcc],
                    ['account' => $examAcc, 'amount' => 5000, 'date' => $baseDate->copy()->addDays(25), 'description' => 'Printing of Exam Sheets (FTF)', 'payment' => $cashAcc],
                    ['account' => $elecAcc, 'amount' => 6000, 'date' => $baseDate->copy()->addDays(35), 'description' => 'Generator fuel (FTF)', 'payment' => $cashAcc],
                    ['account' => $netAcc, 'amount' => 3000, 'date' => $baseDate->copy()->addDays(45), 'description' => 'Broadband Internet fee (FTF)', 'payment' => $bankAcc],
                ]
            ],
            // NSB Category - Taxable (GST/PST + IT)
            [
                'fund_type' => 'nsb',
                'items' => [
                    // Purchases: GST 18%, IT 5.5%
                    ['account' => $examAcc, 'amount' => 25000, 'date' => $baseDate->copy()->addDays(10), 'description' => 'Purchase of Answer Sheets (NSB)', 'payment' => $bankAcc, 'tax_type' => 'purchase', 'gst' => 18.00, 'pst' => 0.00, 'it' => 5.50],
                    ['account' => $elecAcc, 'amount' => 15000, 'date' => $baseDate->copy()->addDays(20), 'description' => 'Purchase of Electrical Wiring (NSB)', 'payment' => $cashAcc, 'tax_type' => 'purchase', 'gst' => 18.00, 'pst' => 0.00, 'it' => 5.50],
                    ['account' => $netAcc, 'amount' => 8000, 'date' => $baseDate->copy()->addDays(30), 'description' => 'Purchase of Router Hardware (NSB)', 'payment' => $bankAcc, 'tax_type' => 'purchase', 'gst' => 18.00, 'pst' => 0.00, 'it' => 5.50],
                    // Services: PST 20%, IT 5.5%
                    ['account' => $salaryAcc, 'amount' => 10000, 'date' => $baseDate->copy()->addDays(40), 'description' => 'Generator Repair Services (NSB)', 'payment' => $cashAcc, 'tax_type' => 'service', 'gst' => 0.00, 'pst' => 20.00, 'it' => 5.50],
                    ['account' => $rentAcc, 'amount' => 18000, 'date' => $baseDate->copy()->addDays(50), 'description' => 'Building Painting Labor (NSB)', 'payment' => $bankAcc, 'tax_type' => 'service', 'gst' => 0.00, 'pst' => 20.00, 'it' => 5.50],
                ]
            ],
            // Special Grant Category - Taxable (GST/PST + IT)
            [
                'fund_type' => 'special_grant',
                'items' => [
                    // Purchases: GST 18%, IT 5.5% - Linked to Lab Grant
                    ['account' => $elecAcc, 'amount' => 30000, 'date' => $baseDate->copy()->addDays(12), 'description' => 'Purchase of LED lights (Special Grant)', 'payment' => $bankAcc, 'tax_type' => 'purchase', 'gst' => 18.00, 'pst' => 0.00, 'it' => 5.50, 'grant' => $labGrant],
                    ['account' => $examAcc, 'amount' => 12000, 'date' => $baseDate->copy()->addDays(22), 'description' => 'Purchase of Science kits (Special Grant)', 'payment' => $cashAcc, 'tax_type' => 'purchase', 'gst' => 18.00, 'pst' => 0.00, 'it' => 5.50, 'grant' => $labGrant],
                    ['account' => $netAcc, 'amount' => 20000, 'date' => $baseDate->copy()->addDays(32), 'description' => 'Purchase of Networking cables (Special Grant)', 'payment' => $bankAcc, 'tax_type' => 'purchase', 'gst' => 18.00, 'pst' => 0.00, 'it' => 5.50, 'grant' => $labGrant],
                    // Services: PST 20%, IT 5.5% - Linked to Sports Grant
                    ['account' => $salaryAcc, 'amount' => 15000, 'date' => $baseDate->copy()->addDays(42), 'description' => 'Lab Installation labor (Special Grant)', 'payment' => $cashAcc, 'tax_type' => 'service', 'gst' => 0.00, 'pst' => 20.00, 'it' => 5.50, 'grant' => $sportsGrant],
                    ['account' => $rentAcc, 'amount' => 22000, 'date' => $baseDate->copy()->addDays(52), 'description' => 'Air conditioner maintenance (Special Grant)', 'payment' => $bankAcc, 'tax_type' => 'service', 'gst' => 0.00, 'pst' => 20.00, 'it' => 5.50, 'grant' => $sportsGrant],
                ]
            ]
        ];

        foreach ($config as $cat) {
            $fundType = $cat['fund_type'];
            foreach ($cat['items'] as $item) {
                DB::transaction(function () use ($fundType, $item, $taxWithheldAcc) {
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

                    $grantId = isset($item['grant']) ? $item['grant']->id : null;

                    // 1. Create Transaction
                    $transaction = Transaction::create([
                        'date' => $item['date'],
                        'description' => $item['description'],
                        'created_at' => $item['date'],
                        'updated_at' => $item['date'],
                    ]);

                    // Dr Expense (Gross Amount)
                    $transaction->lines()->create([
                        'account_id' => $item['account']->id,
                        'debit' => $grossAmount,
                        'credit' => 0,
                        'created_at' => $item['date'],
                        'updated_at' => $item['date'],
                    ]);

                    // Cr Payment Account (Net Amount Paid)
                    $transaction->lines()->create([
                        'account_id' => $item['payment']->id,
                        'debit' => 0,
                        'credit' => $netAmount,
                        'created_at' => $item['date'],
                        'updated_at' => $item['date'],
                    ]);

                    // Cr Tax Withheld liability account (if any taxes withheld)
                    if ($totalTax > 0 && $taxWithheldAcc) {
                        $transaction->lines()->create([
                            'account_id' => $taxWithheldAcc->id,
                            'debit' => 0,
                            'credit' => $totalTax,
                            'created_at' => $item['date'],
                            'updated_at' => $item['date'],
                        ]);
                    }

                    // 2. Create Expense
                    Expense::create([
                        'amount' => $grossAmount,
                        'expense_account_id' => $item['account']->id,
                        'payment_account_id' => $item['payment']->id,
                        'status' => true,
                        'transaction_id' => $transaction->id,
                        'fund_type' => $fundType,
                        'special_grant_id' => $grantId,
                        'receipt_no' => 'REC-' . rand(1000, 9999),
                        'tax_type' => $taxType,
                        'gst_rate' => $gstRate,
                        'pst_rate' => $pstRate,
                        'it_rate' => $itRate,
                        'gst_amount' => $gstAmount,
                        'pst_amount' => $pstAmount,
                        'it_amount' => $itAmount,
                        'net_amount' => $netAmount,
                        'created_at' => $item['date'],
                        'updated_at' => $item['date'],
                    ]);
                });
            }
        }
    }
}
