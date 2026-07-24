<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\Grant;
use App\Models\Account;
use Illuminate\Http\Request;
use Exception;

class GrantController extends Controller
{
    /**
     * Display a listing of grants.
     */
    public function index()
    {
        $session = AcademicSession::current();
        $grants = Grant::with(['installments', 'expenses'])->latest()->get();
        foreach ($grants as $grant) {
            $received = $grant->installments->sum('amount');
            $spent = $grant->expenses->sum('amount');
            $grant->balance = $received - $spent;
        }

        return view('grants.index', compact('grants', 'session'));
    }

    /**
     * Show the form for creating a new grant.
     */
    public function create()
    {
        return view('grants.create');
    }

    /**
     * Store a newly created grant.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'issued_by'   => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            Grant::create([
                'title'       => $request->title,
                'issued_by'   => $request->issued_by,
                'description' => $request->description,
            ]);

            return redirect()->route('grants.index')->with('success', 'Grant type created successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Show a grant with its ledger.
     */
    public function show(Grant $grant)
    {
        $session = AcademicSession::current();
        
        $installments = $grant->installments()->orderBy('received_date', 'desc')->get();
        $expenses = $grant->expenses()->orderBy('created_at', 'desc')->get();

        $session = AcademicSession::current();
        $isNsb = (str_contains(strtolower($grant->title), 'nsb') || str_contains(strtolower($grant->title), 'non-salary'));
        $openingBalance = ($isNsb && $session) ? (int) $session->nsb_start : 0;

        $ledger = collect();

        foreach ($installments as $installment) {
            $ledger->push((object)[
                'type' => 'receipt',
                'id' => $installment->id,
                'date' => $installment->received_date,
                'description' => $installment->description ?? "Installment Payment",
                'amount' => $installment->amount,
                'raw_model' => $installment
            ]);
        }

        foreach ($expenses as $expense) {
            $ledger->push((object)[
                'type'         => 'expense',
                'id'           => $expense->id,
                'date'         => $expense->created_at,
                'description'  => $expense->expenseAccount->name . ($expense->description ? ' — ' . $expense->description : ''),
                'expense_type' => $expense->expense_type,
                'amount'       => $expense->amount, // gross
                'net_amount'   => $expense->net_amount,
                'gst_amount'   => $expense->gst_amount,
                'pst_amount'   => $expense->pst_amount,
                'it_amount'    => $expense->it_amount,
                'receipt_no'   => $expense->receipt_no,
                'raw_model'    => $expense
            ]);
        }

        // Sort by date ascending to calculate running balance starting from opening balance
        $ledger = $ledger->sortBy('date');

        $runningBalance = $openingBalance;
        foreach ($ledger as $item) {
            if ($item->type === 'receipt') {
                $runningBalance += $item->amount;
            } else {
                $runningBalance -= $item->amount;
            }
            $item->running_balance = $runningBalance;
        }

        $balance = $runningBalance;

        // Sort by date descending for display
        $ledger = $ledger->sortByDesc('date');

        $expenseAccounts = Account::where('type', 'expense')->orderBy('name')->get();
        $paymentMethods = Account::where('is_payment_method', true)->orderBy('name')->get();

        return view('grants.show', compact(
            'grant', 
            'installments', 
            'ledger', 
            'balance', 
            'openingBalance',
            'expenseAccounts', 
            'paymentMethods', 
            'session'
        ));
    }

    /**
     * Show the form for editing a grant.
     */
    public function edit(Grant $grant)
    {
        return view('grants.edit', compact('grant'));
    }

    /**
     * Update the specified grant.
     */
    public function update(Request $request, Grant $grant)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'issued_by'   => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $grant->update([
                'title'       => $request->title,
                'issued_by'   => $request->issued_by,
                'description' => $request->description,
            ]);

            return redirect()->route('grants.index')->with('success', 'Grant type updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified grant.
     */
    public function destroy(Grant $grant)
    {
        try {
            $grant->delete();
            return redirect()->route('grants.index')->with('success', 'Grant type deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
