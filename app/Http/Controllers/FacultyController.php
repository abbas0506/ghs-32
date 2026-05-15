<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Exception;

class FacultyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'create', 'store']);
    }

    /**
     * Display a listing of the resource (Public View).
     */
    public function index()
    {
        $query = User::role('teacher')->with('profile');
        
        if (!auth()->check()) {
            $query->whereHas('profile', function($q) {
                $q->where('status', 1);
            });
        }

        $users = $query->get();

        // Sort by BPS descending, then seniority
        $users = $users->sortByDesc(function($user) {
            return $user->profile->bps;
        });

        return view('faculty.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('faculty.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'designation' => 'nullable|string|max:255',
            'bps' => 'nullable|integer',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('password'),
            ]);

            $user->assignRole('teacher');

            $profileData = $request->only(['prefix', 'name', 'phone', 'designation', 'qualification', 'bps', 'joined_at', 'address']);
            $profileData['status'] = auth()->check() ? 1 : 0;

            if ($request->hasFile('photo')) {
                $profileData['photo'] = $request->file('photo')->store('teachers', 'public');
            }

            $user->profile()->create($profileData);

            DB::commit();
            $msg = auth()->check() ? 'Faculty member added successfully.' : 'Your registration has been submitted for approval. It will be visible after review.';
            return redirect()->route('faculty.index')->with('success', $msg);
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error adding faculty: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('faculty.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            $profileData = $request->only(['prefix', 'name', 'phone', 'designation', 'qualification', 'bps', 'joined_at', 'address']);

            if ($request->hasFile('photo')) {
                if ($user->profile->photo) {
                    Storage::disk('public')->delete($user->profile->photo);
                }
                $profileData['photo'] = $request->file('photo')->store('teachers', 'public');
            }

            $user->profile->update($profileData);

            DB::commit();
            return redirect()->route('faculty.index')->with('success', 'Faculty profile updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating faculty: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->profile->photo) {
            Storage::disk('public')->delete($user->profile->photo);
        }

        $user->delete();

        return redirect()->route('faculty.index')->with('success', 'Faculty member removed successfully.');
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->profile->update(['status' => 1]);
        return redirect()->back()->with('success', 'Faculty profile approved successfully.');
    }
}
