<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\Grant;
use App\Models\GrantInstallment;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class GrantInstallmentController extends Controller
{
    /**
     * Show the form to add an installment to a grant.
     */
    public function create(Grant $grant)
    {
        $session = AcademicSession::current();
        return view('grant-installments.create', compact('grant', 'session'));
    }

    /**
     * Store a new installment and auto-save to SMC Bank Account.
     */
    public function store(Request $request, Grant $grant)
    {
        $request->validate([
            'amount'        => 'required|integer|min:1',
            'received_date' => 'required|date',
            'description'   => 'nullable|string|max:255',
            'cheque_no'     => 'nullable|string|max:50',
        ]);

        DB::beginTransaction();
        try {
            // Find SMC Bank Account & Grant Income Account
            $smcAccount = Account::where('code', '1007')->orWhere('name', 'like', '%SMC%')->first();
            $grantIncomeAcc = Account::where('code', '4002')->orWhere('name', 'like', '%Grant%')->first();

            $transaction = null;
            if ($smcAccount && $grantIncomeAcc) {
                $transaction = Transaction::create([
                    'date'        => $request->received_date,
                    'cheque_no'   => $request->cheque_no ?? null,
                    'description' => 'Grant Receipt: ' . $grant->title . ($request->description ? ' (' . $request->description . ')' : ''),
                ]);

                // Dr SMC Bank Account (Asset Inflow)
                $transaction->lines()->create([
                    'account_id' => $smcAccount->id,
                    'debit'      => $request->amount,
                    'credit'     => 0,
                ]);

                // Cr Grant Income (Income)
                $transaction->lines()->create([
                    'account_id' => $grantIncomeAcc->id,
                    'debit'      => 0,
                    'credit'     => $request->amount,
                ]);
            }

            GrantInstallment::create([
                'grant_id'       => $grant->id,
                'amount'         => $request->amount,
                'received_date'  => $request->received_date,
                'description'    => $request->description,
                'cheque_no'      => $request->cheque_no ?? null,
                'transaction_id' => $transaction ? $transaction->id : null,
            ]);

            DB::commit();

            return redirect()
                ->route('grants.show', $grant->id)
                ->with('success', 'Installment saved and auto-credited to SMC Bank Account.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Show the form to edit an installment.
     */
    public function edit(Grant $grant, GrantInstallment $installment)
    {
        return view('grant-installments.edit', compact('grant', 'installment'));
    }

    /**
     * Update an installment.
     */
    public function update(Request $request, Grant $grant, GrantInstallment $installment)
    {
        $request->validate([
            'amount'        => 'required|integer|min:1',
            'received_date' => 'required|date',
            'description'   => 'nullable|string|max:255',
            'cheque_no'     => 'nullable|string|max:50',
        ]);

        DB::beginTransaction();
        try {
            $smcAccount = Account::where('code', '1007')->orWhere('name', 'like', '%SMC%')->first();
            $grantIncomeAcc = Account::where('code', '4002')->orWhere('name', 'like', '%Grant%')->first();

            if ($installment->transaction) {
                $installment->transaction->update([
                    'date'        => $request->received_date,
                    'cheque_no'   => $request->cheque_no ?? null,
                    'description' => 'Grant Receipt: ' . $grant->title . ($request->description ? ' (' . $request->description . ')' : ''),
                ]);

                if ($smcAccount && $grantIncomeAcc) {
                    $installment->transaction->lines()->delete();
                    $installment->transaction->lines()->create([
                        'account_id' => $smcAccount->id,
                        'debit'      => $request->amount,
                        'credit'     => 0,
                    ]);
                    $installment->transaction->lines()->create([
                        'account_id' => $grantIncomeAcc->id,
                        'debit'      => 0,
                        'credit'     => $request->amount,
                    ]);
                }
            }

            $installment->update([
                'amount'        => $request->amount,
                'received_date' => $request->received_date,
                'description'   => $request->description,
                'cheque_no'     => $request->cheque_no ?? null,
            ]);

            DB::commit();

            return redirect()
                ->route('grants.show', $grant->id)
                ->with('success', 'Installment updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Delete an installment.
     */
    public function destroy(Grant $grant, GrantInstallment $installment)
    {
        DB::beginTransaction();
        try {
            if ($installment->transaction) {
                $installment->transaction->delete();
            }
            $installment->delete();

            DB::commit();

            return redirect()
                ->route('grants.show', $grant->id)
                ->with('success', 'Installment deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
