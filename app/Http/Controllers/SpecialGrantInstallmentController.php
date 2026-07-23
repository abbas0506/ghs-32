<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\SpecialGrant;
use App\Models\SpecialGrantInstallment;
use Illuminate\Http\Request;
use Exception;

class SpecialGrantInstallmentController extends Controller
{
    /**
     * Show the form to add an installment to a grant.
     */
    public function create(SpecialGrant $specialGrant)
    {
        $session = AcademicSession::current();
        return view('special-grant-installments.create', compact('specialGrant', 'session'));
    }

    /**
     * Store a new installment.
     */
    public function store(Request $request, SpecialGrant $specialGrant)
    {
        $request->validate([
            'amount'        => 'required|integer|min:1',
            'received_date' => 'required|date',
            'description'   => 'nullable|string|max:255',
        ]);

        try {
            SpecialGrantInstallment::create([
                'special_grant_id' => $specialGrant->id,
                'amount'           => $request->amount,
                'received_date'    => $request->received_date,
                'description'      => $request->description,
            ]);

            return redirect()
                ->route('special-grants.show', $specialGrant->id)
                ->with('success', 'Installment added successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Show the form to edit an installment.
     */
    public function edit(SpecialGrant $specialGrant, SpecialGrantInstallment $installment)
    {
        return view('special-grant-installments.edit', compact('specialGrant', 'installment'));
    }

    /**
     * Update an installment.
     */
    public function update(Request $request, SpecialGrant $specialGrant, SpecialGrantInstallment $installment)
    {
        $request->validate([
            'amount'        => 'required|integer|min:1',
            'received_date' => 'required|date',
            'description'   => 'nullable|string|max:255',
        ]);

        try {
            $installment->update([
                'amount'        => $request->amount,
                'received_date' => $request->received_date,
                'description'   => $request->description,
            ]);

            return redirect()
                ->route('special-grants.show', $specialGrant->id)
                ->with('success', 'Installment updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Delete an installment.
     */
    public function destroy(SpecialGrant $specialGrant, SpecialGrantInstallment $installment)
    {
        try {
            $installment->delete();
            return redirect()
                ->route('special-grants.show', $specialGrant->id)
                ->with('success', 'Installment deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
