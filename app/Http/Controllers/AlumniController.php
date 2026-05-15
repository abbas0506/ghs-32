<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlumniController extends Controller
{
    /**
     * Display a listing of the resource (Public View).
     */
    public function index()
    {
        $query = Alumni::orderBy('display_order', 'asc')->orderBy('name', 'asc');
        
        if (!auth()->check()) {
            $query->approved();
        }

        $alumni = $query->get();
        return view('alumni.index', compact('alumni'));
    }

    /**
     * Show the form for creating a new resource (Admin).
     */
    public function create()
    {
        return view('alumni.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'session' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'display_order' => 'nullable|integer',
        ]);

        $data = $request->all();
        $data['is_approved'] = auth()->check();

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('alumni', 'public');
            $data['photo'] = $path;
        }

        Alumni::create($data);

        $msg = auth()->check() ? 'Alumni record created successfully.' : 'Your profile has been submitted for approval. It will be visible after review.';
        return redirect()->route('alumni.index')->with('success', $msg);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alumni $alumni)
    {
        return view('alumni.edit', compact('alumni'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alumni $alumni)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'session' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'display_order' => 'nullable|integer',
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($alumni->photo) {
                Storage::disk('public')->delete($alumni->photo);
            }
            $path = $request->file('photo')->store('alumni', 'public');
            $data['photo'] = $path;
        }

        $alumni->update($data);

        return redirect()->route('alumni.index')->with('success', 'Alumni record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alumni $alumni)
    {
        if ($alumni->photo) {
            Storage::disk('public')->delete($alumni->photo);
        }
        $alumni->delete();

        return redirect()->route('alumni.index')->with('success', 'Alumni record deleted successfully.');
    }
    public function approve(Alumni $alumni)
    {
        $alumni->update(['is_approved' => true]);
        return redirect()->back()->with('success', 'Alumni profile approved successfully.');
    }
}
