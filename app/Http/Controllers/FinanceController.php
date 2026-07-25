<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\Account;
use App\Models\Expense;
use App\Models\Transaction;
use App\Models\FtfPayment;
use App\Models\FtfVoucher;
use App\Models\SchoolResolution;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    /**
     * Display the finance summary page.
     */
    public function index()
    {
        $currentSession = AcademicSession::current();

        if ($currentSession) {
            $ftfBalance = $currentSession->ftf_balance;
            $nsbBalance = $currentSession->nsb_balance;
            $specialGrantsBalance = $currentSession->special_grants_balance;

            // Live FTF Collection rate: paid vouchers / total vouchers in this session
            $totalPaid = $currentSession->ftfVouchers()
                ->join('ftf_payments', 'ftf_vouchers.id', '=', 'ftf_payments.ftf_voucher_id')
                ->whereNotNull('ftf_payments.payment_date')
                ->count();

            $totalVouchers = $currentSession->ftfVouchers()
                ->join('ftf_payments', 'ftf_vouchers.id', '=', 'ftf_payments.ftf_voucher_id')
                ->count();

            $ftfChange = $totalVouchers > 0 ? round(($totalPaid / $totalVouchers) * 100) : 0;

            // Live NSB budget receipt rate: nsb_collection / nsb_start (allocated budget)
            $nsbChange = $currentSession->nsb_start > 0 
                ? round(($currentSession->nsb_collection / $currentSession->nsb_start) * 100) 
                : 0;

            // Live Special Grants receipt rate: special_grants_collection / special_grants_start
            $specialGrantsChange = $currentSession->special_grants_start > 0
                ? round(($currentSession->special_grants_collection / $currentSession->special_grants_start) * 100)
                : 0;
        } else {
            $ftfBalance = 0;
            $nsbBalance = 0;
            $specialGrantsBalance = 0;
            $ftfChange = 0;
            $nsbChange = 0;
            $specialGrantsChange = 0;
        }

        // Fetch FTF Bank Account and SMC Bank Account
        $ftfAccount = Account::where('code', '1002')->orWhere('name', 'like', '%FTF%')->first();
        $smcAccount = Account::where('code', '1007')->orWhere('name', 'like', '%SMC%')->first();

        // Fetch each grant and compute balance
        $specialGrants = \App\Models\Grant::with(['installments', 'expenses'])->get();
        foreach ($specialGrants as $grant) {
            $received = $grant->installments->sum('amount');
            $spent = $grant->expenses->sum('amount');
            $grant->balance = $received - $spent;
        }

        return view('finance', compact(
            'ftfBalance', 
            'nsbBalance', 
            'specialGrantsBalance', 
            'ftfChange', 
            'nsbChange', 
            'specialGrantsChange', 
            'currentSession',
            'specialGrants',
            'ftfAccount',
            'smcAccount'
        ));
    }

    /**
     * Show FTF Ledger with Opening Balance, Receipts, Withdrawals, and Expenses.
     */
    public function ftfLedger()
    {
        $session = AcademicSession::current();
        $openingBalance = $session ? (int) $session->ftf_start : 0;

        $ftfAccount = Account::where('code', '1002')->first();
        $cashAccount = Account::where('code', '1001')->first();
        $ftfIncomeAccount = Account::where('code', '4001')->first();

        $ledger = collect();

        // 2. Manual transactions on the FTF Bank Account
        $manualTransactions = collect();
        if ($ftfAccount) {
            $manualTransactions = Transaction::whereHas('lines', function($q) use ($ftfAccount) {
                $q->where('account_id', $ftfAccount->id);
            })->with('lines.account')->get();
        }

        foreach ($manualTransactions as $mtxn) {
            $ftfLine = $mtxn->lines->where('account_id', $ftfAccount->id)->first();
            if ($ftfLine) {
                $isDebit = $ftfLine->debit > 0;
                $ledger->push((object)[
                    'type'         => 'manual_transaction',
                    'id'           => 'txn-' . $mtxn->id,
                    'date'         => \Carbon\Carbon::parse($mtxn->date),
                    'description'  => $mtxn->description . ($isDebit ? ' (FTF Bank Deposit)' : ' (FTF Bank Withdrawal)'),
                    'expense_type' => 'transfer',
                    'amount'       => $isDebit ? $ftfLine->debit : $ftfLine->credit,
                    'net_amount'   => $isDebit ? $ftfLine->debit : $ftfLine->credit,
                    'gst_amount'   => 0,
                    'pst_amount'   => 0,
                    'it_amount'    => 0,
                    'gst_rate'     => 0,
                    'pst_rate'     => 0,
                    'it_rate'      => 0,
                    'receipt_no'   => $mtxn->cheque_no ?? 'TRANSFER',
                    'resolution_no'   => null,
                    'resolution_date' => null,
                    'raw_model'    => $mtxn,
                    'txn_direction'=> $isDebit ? 'debit' : 'credit'
                ]);
            }
        }

        // 3. Expenses paid out of Cash associated with FTF fund type
        $expenses = Expense::where('fund_type', 'ftf')
            ->with('expenseAccount')
            ->get();

        foreach ($expenses as $expense) {
            $ledger->push((object)[
                'type'         => 'expense',
                'id'           => 'exp-' . $expense->id,
                'date'         => \Carbon\Carbon::parse($expense->created_at),
                'description'  => $expense->expenseAccount->name . ($expense->description ? ' — ' . $expense->description : ''),
                'expense_type' => $expense->expense_type,
                'amount'       => $expense->amount, // gross
                'net_amount'   => $expense->net_amount,
                'gst_amount'   => $expense->gst_amount,
                'pst_amount'   => $expense->pst_amount,
                'it_amount'    => $expense->it_amount,
                'gst_rate'     => $expense->gst_rate,
                'pst_rate'     => $expense->pst_rate,
                'it_rate'      => $expense->it_rate,
                'receipt_no'   => $expense->receipt_no,
                'resolution_no'   => $expense->schoolResolution ? $expense->schoolResolution->number : null,
                'resolution_date' => $expense->schoolResolution ? $expense->schoolResolution->date : null,
                'raw_model'    => $expense
            ]);
        }

        // Sort by date ascending to calculate running balance starting from opening balance
        $ledger = $ledger->sortBy(function ($item) {
            return $item->date->timestamp;
        })->values();

        $runningBalance = $openingBalance;
        foreach ($ledger as $item) {
            if ($item->type === 'receipt') {
                $runningBalance += $item->amount;
            } elseif ($item->type === 'expense') {
                $runningBalance -= $item->amount;
            } elseif ($item->type === 'manual_transaction') {
                if ($item->txn_direction === 'credit') {
                    $runningBalance -= $item->amount;
                } else {
                    $runningBalance += $item->amount;
                }
            }
            $item->running_balance = $runningBalance;
        }

        $balance = $runningBalance;

        // Totals
        $totalReceived = $manualTransactions->sum(function($txn) use ($ftfAccount) {
            $line = $txn->lines->where('account_id', $ftfAccount->id)->first();
            return ($line && $line->debit > 0) ? $line->debit : 0;
        });
        $totalWithdrawn = $manualTransactions->sum(function($txn) use ($ftfAccount) {
            $line = $txn->lines->where('account_id', $ftfAccount->id)->first();
            return ($line && $line->credit > 0) ? $line->credit : 0;
        });
            
        $totalGross = $expenses->sum('amount') + $totalWithdrawn;

        $expenseAccounts = Account::where('type', 'expense')->whereNotNull('parent_id')->orderBy('name')->get();
        $resolutions = SchoolResolution::orderBy('number')->get();

        return view('finance.ftf', compact(
            'session',
            'openingBalance',
            'ledger',
            'balance',
            'totalReceived',
            'totalGross',
            'totalWithdrawn',
            'ftfAccount',
            'cashAccount',
            'ftfIncomeAccount',
            'expenseAccounts',
            'resolutions'
        ));
    }
}
