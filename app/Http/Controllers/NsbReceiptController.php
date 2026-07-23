<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\NsbReceipt;
use Illuminate\Http\Request;
use Exception;

class NsbReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     * Scoped to the active session via received_date range.
     */
    public function index()
    {
        $session = AcademicSession::current();

        $receiptsQuery = NsbReceipt::query();
        $expensesQuery = \App\Models\Expense::with(['expenseAccount', 'paymentAccount'])
            ->where('fund_type', 'nsb');

        if ($session) {
            $startDate = $session->start_date->toDateString();
            $endDate = $session->end_date->toDateString();

            $receiptsQuery->whereBetween('received_date', [$startDate, $endDate]);
            $expensesQuery->whereBetween('created_at', [
                $session->start_date->startOfDay()->toDateTimeString(),
                $session->end_date->endOfDay()->toDateTimeString(),
            ]);
        }

        $receipts = $receiptsQuery->get();
        $expenses = $expensesQuery->get();

        // Build a unified ledger of items sorted by date ascending
        $ledger = collect();

        foreach ($receipts as $receipt) {
            $ledger->push((object)[
                'type' => 'receipt',
                'id' => $receipt->id,
                'date' => $receipt->received_date,
                'description' => $receipt->description ?? "Q{$receipt->quarter} NSB Receipt",
                'quarter' => $receipt->quarter,
                'amount' => $receipt->amount,
                'raw_model' => $receipt
            ]);
        }

        foreach ($expenses as $expense) {
            $ledger->push((object)[
                'type' => 'expense',
                'id' => $expense->id,
                'date' => $expense->created_at,
                'description' => $expense->expenseAccount->name . ' - Expense',
                'amount' => $expense->amount, // gross
                'raw_model' => $expense
            ]);
        }

        // Sort ascending by date for chronological balance calculations
        $ledger = $ledger->sortBy('date')->values();

        // Calculate running balance starting with opening balance (nsb_start)
        $runningBalance = $session ? $session->nsb_start : 0;
        
        foreach ($ledger as $item) {
            if ($item->type === 'receipt') {
                $runningBalance += $item->amount;
            } else {
                $runningBalance -= $item->amount;
            }
            $item->balance_after = $runningBalance;
        }

        return view('nsb-receipts.index', compact('receipts', 'session', 'ledger'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('nsb-receipts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'quarter'       => 'required|integer|min:1|max:4',
            'amount'        => 'required|integer|min:1',
            'received_date' => 'required|date',
            'description'   => 'nullable|string|max:255',
        ]);

        try {
            NsbReceipt::create([
                'quarter'       => $request->quarter,
                'amount'        => $request->amount,
                'received_date' => $request->received_date,
                'description'   => $request->description,
            ]);

            return redirect()->route('nsb-receipts.index')->with('success', 'NSB Receipt created successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(NsbReceipt $nsbReceipt)
    {
        return view('nsb-receipts.show', compact('nsbReceipt'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NsbReceipt $nsbReceipt)
    {
        return view('nsb-receipts.edit', compact('nsbReceipt'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NsbReceipt $nsbReceipt)
    {
        $request->validate([
            'quarter'       => 'required|integer|min:1|max:4',
            'amount'        => 'required|integer|min:1',
            'received_date' => 'required|date',
            'description'   => 'nullable|string|max:255',
        ]);

        try {
            $nsbReceipt->update([
                'quarter'       => $request->quarter,
                'amount'        => $request->amount,
                'received_date' => $request->received_date,
                'description'   => $request->description,
            ]);

            return redirect()->route('nsb-receipts.index')->with('success', 'NSB Receipt updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NsbReceipt $nsbReceipt)
    {
        try {
            $nsbReceipt->delete();
            return redirect()->route('nsb-receipts.index')->with('success', 'NSB Receipt deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
