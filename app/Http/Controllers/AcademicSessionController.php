<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use Illuminate\Http\Request;
use Exception;

class AcademicSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sessions = AcademicSession::orderBy('start_date', 'desc')->get();
        return view('academic-sessions.index', compact('sessions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('academic-sessions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:academic_sessions,name',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'ftf_start' => 'required|integer|min:0',
            'nsb_start' => 'required|integer|min:0',
            'is_current' => 'nullable|boolean',
        ]);

        try {
            $session = AcademicSession::create([
                'name' => $request->name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'ftf_start' => $request->ftf_start,
                'nsb_start' => $request->nsb_start,
                'is_current' => $request->has('is_current') ? (bool) $request->is_current : false,
            ]);

            // Sync HTTP session if active
            if ($session->is_current) {
                session(['academic_session_id' => $session->id]);
            }

            return redirect()->route('academic-sessions.index')->with('success', 'Academic Session successfully created.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $session = AcademicSession::findOrFail($id);
        return view('academic-sessions.show', compact('session'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $session = AcademicSession::findOrFail($id);
        return view('academic-sessions.edit', compact('session'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $session = AcademicSession::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:50|unique:academic_sessions,name,' . $session->id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'ftf_start' => 'required|integer|min:0',
            'nsb_start' => 'required|integer|min:0',
            'is_current' => 'nullable|boolean',
        ]);

        try {
            $session->update([
                'name' => $request->name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'ftf_start' => $request->ftf_start,
                'nsb_start' => $request->nsb_start,
                'is_current' => $request->has('is_current') ? (bool) $request->is_current : false,
            ]);

            // Sync HTTP session if active
            if ($session->is_current) {
                session(['academic_session_id' => $session->id]);
            }

            return redirect()->route('academic-sessions.index')->with('success', 'Academic Session successfully updated.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $session = AcademicSession::findOrFail($id);

        try {
            $session->delete();
            return redirect()->route('academic-sessions.index')->with('success', 'Academic Session successfully deleted.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Switch the active academic session.
     */
    public function switchSession($id)
    {
        $session = AcademicSession::findOrFail($id);
        $session->update(['is_current' => true]);
        session(['academic_session_id' => $session->id]);

        return redirect()->back()->with('success', 'Switched to session: ' . $session->name);
    }
}
