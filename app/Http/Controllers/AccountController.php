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
        $grants = \App\Models\Grant::orderBy('title')->get();

        return view('accounts.show', compact('account', 'lines', 'balance', 'otherAccounts', 'grants'));
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
            'contra_account_id'  => 'nullable|exists:accounts,id',
            'cheque_no'          => 'nullable|string|max:50',
            'description'        => 'nullable|string|max:255',
            'grant_id'           => $account->code === '1007' ? 'required|exists:grants,id' : 'nullable|exists:grants,id',
        ]);

        // Require cheque number for bank withdrawals
        $isBank = in_array($account->code, ['1002', '1007']);
        if ($request->txn_type === 'credit' && $isBank) {
            $request->validate([
                'cheque_no' => 'required|string|max:50',
            ], [
                'cheque_no.required' => 'Cheque number is required for bank cheque withdrawals.',
            ]);
        }

        $contraAccId = $request->contra_account_id;
        if (empty($contraAccId) && $request->txn_type === 'credit' && $isBank) {
            $cashAccount = Account::where('code', '1001')->first();
            $contraAccId = $cashAccount ? $cashAccount->id : null;
        }

        if (empty($contraAccId)) {
            $request->validate([
                'contra_account_id' => 'required|exists:accounts,id'
            ]);
        }

        DB::beginTransaction();
        try {
            $txn = Transaction::create([
                'date'        => $request->date,
                'cheque_no'   => $request->cheque_no ?? null,
                'description' => $request->description ?? 'Manual Transaction (' . ucfirst($request->txn_type) . ')',
                'grant_id'    => $request->grant_id ?? null,
            ]);

            $contraAcc = Account::findOrFail($contraAccId);

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

            if ($request->has('redirect_to')) {
                return redirect($request->redirect_to)->with('success', 'Manual transaction posted successfully.');
            }
            return redirect()->route('accounts.show', $account->id)->with('success', 'Manual transaction posted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Update a manual transaction.
     */
    public function updateTransaction(Request $request, Transaction $transaction)
    {
        $account = Account::findOrFail($request->account_id);

        $request->validate([
            'date'              => 'required|date',
            'amount'            => 'required|numeric|min:1',
            'description'       => 'nullable|string|max:255',
            'cheque_no'         => 'nullable|string|max:50',
            'account_id'        => 'required|exists:accounts,id',
            'contra_account_id' => 'nullable|exists:accounts,id',
            'txn_type'          => 'required|in:debit,credit',
            'grant_id'          => $account->code === '1007' ? 'required|exists:grants,id' : 'nullable|exists:grants,id',
        ]);

        $isBank = in_array($account->code, ['1002', '1007']);
        if ($request->txn_type === 'credit' && $isBank) {
            $request->validate([
                'cheque_no' => 'required|string|max:50',
            ], [
                'cheque_no.required' => 'Cheque number is required for bank cheque withdrawals.',
            ]);
        }

        $contraAccId = $request->contra_account_id;
        if (empty($contraAccId) && $request->txn_type === 'credit' && $isBank) {
            $cashAccount = Account::where('code', '1001')->first();
            $contraAccId = $cashAccount ? $cashAccount->id : null;
        }

        if (empty($contraAccId)) {
            $request->validate([
                'contra_account_id' => 'required|exists:accounts,id'
            ]);
        }

        DB::beginTransaction();
        try {
            $transaction->update([
                'date'        => $request->date,
                'cheque_no'   => $request->cheque_no ?? null,
                'description' => $request->description ?? 'Manual Transaction (' . ucfirst($request->txn_type) . ')',
                'grant_id'    => $request->grant_id ?? null,
            ]);

            $contraAcc = Account::findOrFail($contraAccId);

            // Replace transaction lines
            $transaction->lines()->delete();

            if ($request->txn_type === 'debit') {
                $transaction->lines()->create([
                    'account_id' => $account->id,
                    'debit'      => $request->amount,
                    'credit'     => 0,
                ]);
                $transaction->lines()->create([
                    'account_id' => $contraAcc->id,
                    'debit'      => 0,
                    'credit'     => $request->amount,
                ]);
            } else {
                $transaction->lines()->create([
                    'account_id' => $account->id,
                    'debit'      => 0,
                    'credit'     => $request->amount,
                ]);
                $transaction->lines()->create([
                    'account_id' => $contraAcc->id,
                    'debit'      => $request->amount,
                    'credit'     => 0,
                ]);
            }

            DB::commit();

            if ($request->has('redirect_to')) {
                return redirect($request->redirect_to)->with('success', 'Transaction updated successfully.');
            }
            return redirect()->route('accounts.show', $account->id)->with('success', 'Transaction updated successfully.');
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
