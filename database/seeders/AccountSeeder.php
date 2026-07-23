<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        // Assets
        $asset = Account::create([
            'code' => '1000',
            'name' => 'Asset',
            'type' => 'asset',
            'parent_id' => null,
        ]);

        Account::insert([
            [
                'code' => '1001',
                'name' => 'Cash',
                'type' => 'asset',
                'is_payment_method' => 1,
                'parent_id' => $asset->id,
            ],
            [
                'code' => '1002',
                'name' => 'Bank',
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
                'name' => 'Easypaisa',
                'type' => 'asset',
                'is_payment_method' => 1,
                'parent_id' => $asset->id,
            ],
            [
                'code' => '1005',
                'name' => 'Fee Receivable',
                'type' => 'asset',
                'is_payment_method' => 0,
                'parent_id' => $asset->id,
            ],
            [
                'code' => '1006',
                'name' => 'Furniture',
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
                'name' => 'Salary Enxpenses',
                'type' => 'expense',
                'parent_id' => $expenses->id,
            ],
            [
                'code' => '5002',
                'name' => 'Exam Expenses',
                'type' => 'expense',
                'parent_id' => $expenses->id,
            ],
            [
                'code' => '5003',
                'name' => 'Office Rent',
                'type' => 'expense',
                'parent_id' => $expenses->id,
            ],
            [
                'code' => '5004',
                'name' => 'Electricity',
                'type' => 'expense',
                'parent_id' => $expenses->id,
            ],
            [
                'code' => '5005',
                'name' => 'Internet',
                'type' => 'expense',
                'parent_id' => $expenses->id,
            ],
        ]);
    }
}
