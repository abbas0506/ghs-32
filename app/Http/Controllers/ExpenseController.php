<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Expense;
use App\Models\Transaction;
use App\Models\TransactionLine;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Expense::class);

        $expenses = Expense::with(['expenseAccount', 'paymentAccount', 'specialGrant'])
            ->latest()
            ->get();
        $specialGrants = \App\Models\SpecialGrant::all();

        return view('expenses.index', compact('expenses', 'specialGrants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Expense::class);

        $expenseAccounts = Account::where('type', 'expense')->get();
        $paymentMethods  = Account::where('is_payment_method', true)->get();
        $specialGrants   = \App\Models\SpecialGrant::all();

        return view('expenses.create', compact('expenseAccounts', 'paymentMethods', 'specialGrants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'expense_account_id' => 'required|exists:accounts,id',
            'payment_account_id' => 'nullable|exists:accounts,id',
            'fund_type' => 'nullable|string|in:ftf,nsb,special_grant,grant',
            'grant_id' => 'nullable|exists:grants,id',
            'special_grant_id' => 'nullable|exists:grants,id',
            'receipt_no' => 'required|string|max:50',
            'cheque_no' => 'nullable|string|max:50',
            'tax_type' => 'required|string|in:none,purchase,service',
            'gst_rate' => 'nullable|numeric|min:0|max:100',
            'pst_rate' => 'nullable|numeric|min:0|max:100',
            'it_rate' => 'nullable|numeric|min:0|max:100',
            'expense_type' => 'required|string|in:purchase,service,utility,other',
            'description' => 'nullable|string|max:255',
        ]);

        $fundType = $request->fund_type ?? 'grant';
        if ($fundType === 'nsb' || $fundType === 'special_grant') {
            $fundType = 'grant';
        }

        $grantId = $request->grant_id ?? $request->special_grant_id;

        if ($fundType === 'grant' && !$grantId) {
            $nsbGrant = \App\Models\Grant::where('title', 'like', '%NSB%')->orWhere('title', 'like', '%Non-Salary%')->first();
            $grantId = $nsbGrant ? $nsbGrant->id : null;
        }

        $taxType = $request->tax_type;
        $netAmount = $request->amount; // Taken as net amount paid

        $gstRate = 0.00;
        $pstRate = 0.00;
        $itRate = 0.00;

        if ($fundType !== 'ftf' && $taxType !== 'none') {
            if ($taxType === 'purchase') {
                $gstRate = $request->filled('gst_rate') ? (float) $request->gst_rate : 18.00;
                $itRate = $request->filled('it_rate') ? (float) $request->it_rate : 5.50;
            } elseif ($taxType === 'service') {
                $pstRate = $request->filled('pst_rate') ? (float) $request->pst_rate : 20.00;
                $itRate = $request->filled('it_rate') ? (float) $request->it_rate : 5.50;
            }
        }

        $rateSum = $gstRate + $pstRate + $itRate;
        if ($rateSum >= 100) {
            return redirect()->back()->withErrors('Tax rate sum cannot be 100% or more.')->withInput();
        }

        $grossAmount = round($netAmount / (1 - $rateSum / 100));

        $gstAmount = round(($grossAmount * $gstRate) / 100);
        $pstAmount = round(($grossAmount * $pstRate) / 100);
        $itAmount = round(($grossAmount * $itRate) / 100);

        $totalTax = $gstAmount + $pstAmount + $itAmount;
        $grossAmount = $netAmount + $totalTax;

        $paymentAccountId = $request->payment_account_id;
        if (!$paymentAccountId) {
            $paymentAccountId = Account::where('is_payment_method', true)->first()->id;
        }

        DB::beginTransaction();
        try {
            $transaction = Transaction::create([
                'date' => now(),
                'cheque_no' => $request->cheque_no ?? null,
                'description' => Account::find($request->expense_account_id)->name . ' expense (' . strtoupper($fundType) . ')',
            ]);

            // Dr Expense
            $transaction->lines()->create([
                'account_id' => $request->expense_account_id,
                'debit' => $grossAmount,
                'credit' => 0,
            ]);

            // Cr Bank/Cash
            $transaction->lines()->create([
                'account_id' => $paymentAccountId,
                'debit' => 0,
                'credit' => $netAmount,
            ]);

            // Cr Tax Withheld Liability
            if ($totalTax > 0) {
                $taxWithheldAcc = Account::where('code', '2003')->first();
                if ($taxWithheldAcc) {
                    $transaction->lines()->create([
                        'account_id' => $taxWithheldAcc->id,
                        'debit' => 0,
                        'credit' => $totalTax,
                    ]);
                }
            }

            Expense::create([
                'amount' => $grossAmount,
                'expense_account_id' => $request->expense_account_id,
                'payment_account_id' => $paymentAccountId,
                'status' => 1,
                'transaction_id' => $transaction->id,
                'fund_type' => $fundType,
                'grant_id' => $fundType === 'grant' ? $grantId : null,
                'receipt_no' => $request->receipt_no,
                'cheque_no' => $request->cheque_no ?? null,
                'tax_type' => $taxType,
                'gst_rate' => $gstRate,
                'pst_rate' => $pstRate,
                'it_rate' => $itRate,
                'gst_amount' => $gstAmount,
                'pst_amount' => $pstAmount,
                'it_amount' => $itAmount,
                'net_amount' => $netAmount,
                'expense_type' => $request->expense_type,
                'description' => $request->description,
            ]);

            DB::commit();

            if ($request->has('redirect_to')) {
                return redirect($request->redirect_to)->with('success', 'Successfully created');
            }
            return redirect()->route('expenses.index')->with('success', 'Successfully created');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        //
    }
}
