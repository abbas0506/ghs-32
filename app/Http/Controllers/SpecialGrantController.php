<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\SpecialGrant;
use Illuminate\Http\Request;
use Exception;

class SpecialGrantController extends Controller
{
    /**
     * Display a listing of grants.
     * Session context shown but grants themselves span sessions; installments are date-scoped.
     */
    public function index()
    {
        $session = AcademicSession::current();

        // Show all grants that have at least one installment within the current session,
        // OR show all grants if no session is active.
        $grants = SpecialGrant::with(['installments' => function ($q) use ($session) {
            if ($session) {
                $q->whereBetween('received_date', [
                    $session->start_date->toDateString(),
                    $session->end_date->toDateString(),
                ]);
            }
        }])->withCount('installments')
          ->latest()
          ->get();

        return view('special-grants.index', compact('grants', 'session'));
    }

    /**
     * Show the form for creating a new grant.
     */
    public function create()
    {
        return view('special-grants.create');
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
            SpecialGrant::create([
                'title'       => $request->title,
                'issued_by'   => $request->issued_by,
                'description' => $request->description,
            ]);

            return redirect()->route('special-grants.index')->with('success', 'Special Grant created successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Show a grant with its installments.
     */
    public function show(SpecialGrant $specialGrant)
    {
        $session      = AcademicSession::current();
        $installments = $specialGrant->installments()->orderBy('received_date', 'desc')->get();

        return view('special-grants.show', compact('specialGrant', 'installments', 'session'));
    }

    /**
     * Show the form for editing a grant.
     */
    public function edit(SpecialGrant $specialGrant)
    {
        return view('special-grants.edit', compact('specialGrant'));
    }

    /**
     * Update the specified grant.
     */
    public function update(Request $request, SpecialGrant $specialGrant)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'issued_by'   => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $specialGrant->update([
                'title'       => $request->title,
                'issued_by'   => $request->issued_by,
                'description' => $request->description,
            ]);

            return redirect()->route('special-grants.index')->with('success', 'Special Grant updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified grant (and cascade its installments).
     */
    public function destroy(SpecialGrant $specialGrant)
    {
        try {
            $specialGrant->delete();
            return redirect()->route('special-grants.index')->with('success', 'Special Grant deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
