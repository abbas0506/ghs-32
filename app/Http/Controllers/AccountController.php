<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\TransactionLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class AccountController extends Controller
{
    /**
     * Display a listing of all accounts with FTF and SMC Bank summaries.
     */
    public function index()
    {
        $ftfAccount = Account::where('code', '1002')->orWhere('name', 'like', '%FTF%')->first();
        $smcAccount = Account::where('code', '1007')->orWhere('name', 'like', '%SMC%')->first();

        // Calculate live balances
        $ftfBalance = $ftfAccount ? $ftfAccount->balance() : 0;
        $smcBalance = $smcAccount ? $smcAccount->balance() : 0;

        $ftfDeposits = $ftfAccount ? $ftfAccount->lines()->sum('debit') : 0;
        $ftfWithdrawals = $ftfAccount ? $ftfAccount->lines()->sum('credit') : 0;

        $smcReceipts = $smcAccount ? $smcAccount->lines()->sum('debit') : 0;
        $smcExpenses = $smcAccount ? $smcAccount->lines()->sum('credit') : 0;

        $accounts = Account::with('parent')->orderBy('code')->get()->groupBy('type');
        $allAccounts = Account::orderBy('code')->get();

        return view('accounts.index', compact(
            'ftfAccount',
            'smcAccount',
            'ftfBalance',
            'smcBalance',
            'ftfDeposits',
            'ftfWithdrawals',
            'smcReceipts',
            'smcExpenses',
            'accounts',
            'allAccounts'
        ));
    }

    /**
     * Store a newly created account in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code'              => 'required|string|max:20|unique:accounts,code',
            'name'              => 'required|string|max:255',
            'type'              => 'required|in:asset,liability,equity,income,expense',
            'is_payment_method' => 'nullable|boolean',
            'parent_id'         => 'nullable|exists:accounts,id',
        ]);

        try {
            Account::create([
                'code'              => $request->code,
                'name'              => $request->name,
                'type'              => $request->type,
                'is_payment_method' => $request->has('is_payment_method') ? 1 : 0,
                'parent_id'         => $request->parent_id,
            ]);

            return redirect()->route('accounts.index')->with('success', 'Account created successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Display detailed ledger of a specific account with manual transaction posting.
     */
    public function show(Account $account)
    {
        $lines = TransactionLine::where('account_id', $account->id)
            ->with('transaction')
            ->get()
            ->sortBy(function ($line) {
                return $line->transaction ? $line->transaction->date : $line->created_at;
            });

        $balance = 0;
        foreach ($lines as $line) {
            if (in_array($account->type, ['asset', 'expense'])) {
                $balance += $line->debit - $line->credit;
            } else {
                $balance += $line->credit - $line->debit;
            }
            $line->running_balance = $balance;
        }

        // Sort descending for display
        $lines = $lines->sortByDesc(function ($line) {
            return $line->transaction ? $line->transaction->date : $line->created_at;
        });

        $otherAccounts = Account::where('id', '!=', $account->id)->orderBy('name')->get();

        return view('accounts.show', compact('account', 'lines', 'balance', 'otherAccounts'));
    }

    /**
     * Update the specified account in storage.
     */
    public function update(Request $request, Account $account)
    {
        $request->validate([
            'code'              => 'required|string|max:20|unique:accounts,code,' . $account->id,
            'name'              => 'required|string|max:255',
            'type'              => 'required|in:asset,liability,equity,income,expense',
            'is_payment_method' => 'nullable|boolean',
            'parent_id'         => 'nullable|exists:accounts,id',
        ]);

        try {
            $account->update([
                'code'              => $request->code,
                'name'              => $request->name,
                'type'              => $request->type,
                'is_payment_method' => $request->has('is_payment_method') ? 1 : 0,
                'parent_id'         => $request->parent_id,
            ]);

            return redirect()->route('accounts.index')->with('success', 'Account updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified account from storage.
     */
    public function destroy(Account $account)
    {
        try {
            if ($account->lines()->count() > 0) {
                return redirect()->back()->withErrors('Cannot delete account with existing transaction entries.');
            }

            $account->delete();
            return redirect()->route('accounts.index')->with('success', 'Account deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Store a manual transaction (Dr or Cr) to an account.
     */
    public function storeTransaction(Request $request, Account $account)
    {
        $request->validate([
            'date'               => 'required|date',
            'txn_type'           => 'required|in:debit,credit',
            'amount'             => 'required|numeric|min:1',
            'contra_account_id'  => 'required|exists:accounts,id',
            'cheque_no'          => 'nullable|string|max:50',
            'description'        => 'nullable|string|max:255',
        ]);

        // Require cheque number for bank withdrawals
        if ($request->txn_type === 'credit' && in_array($account->code, ['1002', '1007'])) {
            $request->validate([
                'cheque_no' => 'required|string|max:50',
            ], [
                'cheque_no.required' => 'Cheque number is required for bank cheque withdrawals.',
            ]);
        }

        DB::beginTransaction();
        try {
            $txn = Transaction::create([
                'date'        => $request->date,
                'cheque_no'   => $request->cheque_no ?? null,
                'description' => $request->description ?? 'Manual Transaction (' . ucfirst($request->txn_type) . ')',
            ]);

            $contraAcc = Account::findOrFail($request->contra_account_id);

            if ($request->txn_type === 'debit') {
                // Dr Primary Account
                $txn->lines()->create([
                    'account_id' => $account->id,
                    'debit'      => $request->amount,
                    'credit'     => 0,
                ]);
                // Cr Contra Account
                $txn->lines()->create([
                    'account_id' => $contraAcc->id,
                    'debit'      => 0,
                    'credit'     => $request->amount,
                ]);
            } else {
                // Cr Primary Account (Withdrawal)
                $txn->lines()->create([
                    'account_id' => $account->id,
                    'debit'      => 0,
                    'credit'     => $request->amount,
                ]);
                // Dr Contra Account
                $txn->lines()->create([
                    'account_id' => $contraAcc->id,
                    'debit'      => $request->amount,
                    'credit'     => 0,
                ]);
            }

            DB::commit();

            return redirect()->route('accounts.show', $account->id)->with('success', 'Manual transaction posted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Delete a manual transaction.
     */
    public function destroyTransaction(Transaction $transaction)
    {
        DB::beginTransaction();
        try {
            $transaction->lines()->delete();
            $transaction->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Transaction deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
