<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ===== ASSETS =====
        $asset = Account::create([
            'code' => '1000',
            'name' => 'Assets',
            'type' => 'asset',
            'parent_id' => null,
        ]);

        Account::insert([
            [
                'code' => '1001',
                'name' => 'Cash Account',
                'type' => 'asset',
                'is_payment_method' => 1,
                'parent_id' => $asset->id,
            ],
            [
                'code' => '1002',
                'name' => 'FTF Bank Account',
                'type' => 'asset',
                'is_payment_method' => 1,
                'parent_id' => $asset->id,
            ],
            [
                'code' => '1007',
                'name' => 'SMC Bank Account',
                'type' => 'asset',
                'is_payment_method' => 1,
                'parent_id' => $asset->id,
            ],
            [
                'code' => '1003',
                'name' => 'Jazz Cash',
                'type' => 'asset',
                'is_payment_method' => 1,
                'parent_id' => $asset->id,
            ],
            [
                'code' => '1004',
                'name' => 'Easy Paisa',
                'type' => 'asset',
                'is_payment_method' => 1,
                'parent_id' => $asset->id,
            ],
            [
                'code' => '1005',
                'name' => 'Accounts Receivable',
                'type' => 'asset',
                'is_payment_method' => 0,
                'parent_id' => $asset->id,
            ],
            [
                'code' => '1006',
                'name' => 'Inventory',
                'type' => 'asset',
                'is_payment_method' => 0,
                'parent_id' => $asset->id,
            ],
        ]);

        // ===== LIABILITIES =====
        $liability = Account::create([
            'code' => '2000',
            'name' => 'Liabilities',
            'type' => 'liability',
            'parent_id' => null,
        ]);

        Account::insert([
            [
                'code' => '2001',
                'name' => 'Salary Payable',
                'type' => 'liability',
                'parent_id' => $liability->id,
            ],
            [
                'code' => '2002',
                'name' => 'Accounts Payable',
                'type' => 'liability',
                'parent_id' => $liability->id,
            ],
            [
                'code' => '2003',
                'name' => 'Tax Withheld Payable',
                'type' => 'liability',
                'parent_id' => $liability->id,
            ],
        ]);

        // ===== EQUITY =====
        $equity = Account::create([
            'code' => '3000',
            'name' => 'Equity',
            'type' => 'equity',
            'parent_id' => null,
        ]);

        Account::insert([
            [
                'code' => '3001',
                'name' => 'Capital',
                'type' => 'equity',
                'parent_id' => $equity->id,
            ],
            [
                'code' => '3002',
                'name' => 'Drawings',
                'type' => 'equity',
                'parent_id' => $equity->id,
            ],
            [
                'code' => '3003',
                'name' => 'Retained Earnings',
                'type' => 'equity',
                'parent_id' => $equity->id,
            ],
        ]);

        // ===== INCOME =====
        $income = Account::create([
            'code' => '4000',
            'name' => 'Income',
            'type' => 'income',
            'parent_id' => null,
        ]);

        Account::insert([
            [
                'code' => '4001',
                'name' => 'Fee Income',
                'type' => 'income',
                'parent_id' => $income->id,
            ],
            [
                'code' => '4002',
                'name' => 'Grant Receipts Income',
                'type' => 'income',
                'parent_id' => $income->id,
            ],
        ]);

        // ===== EXPENSES =====
        $expenses = Account::create([
            'code' => '5000',
            'name' => 'Expenses',
            'type' => 'expense',
            'parent_id' => null,
        ]);

        Account::insert([
            [
                'code' => '5001',
                'name' => 'Electricity',
                'type' => 'expense',
                'parent_id' => $expenses->id,
            ],
            [
                'code' => '5002',
                'name' => 'Internet',
                'type' => 'expense',
                'parent_id' => $expenses->id,
            ],
            [
                'code' => '5003',
                'name' => 'Exams',
                'type' => 'expense',
                'parent_id' => $expenses->id,
            ],
            [
                'code' => '5004',
                'name' => 'Computer Lab',
                'type' => 'expense',
                'parent_id' => $expenses->id,
            ],
            [
                'code' => '5005',
                'name' => 'Science Lab',
                'type' => 'expense',
                'parent_id' => $expenses->id,
            ],
            [
                'code' => '5006',
                'name' => 'Furniture',
                'type' => 'expense',
                'parent_id' => $expenses->id,
            ],
            [
                'code' => '5007',
                'name' => 'Sports',
                'type' => 'expense',
                'parent_id' => $expenses->id,
            ],
            [
                'code' => '5008',
                'name' => 'Maintenance',
                'type' => 'expense',
                'parent_id' => $expenses->id,
            ],
            [
                'code' => '5009',
                'name' => 'Wages',
                'type' => 'expense',
                'parent_id' => $expenses->id,
            ],
            [
                'code' => '5010',
                'name' => 'Renovation',
                'type' => 'expense',
                'parent_id' => $expenses->id,
            ],
            [
                'code' => '5011',
                'name' => 'Others',
                'type' => 'expense',
                'parent_id' => $expenses->id,
            ],
        ]);
    }
}
