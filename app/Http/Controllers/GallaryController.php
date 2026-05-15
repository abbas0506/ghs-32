<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GallaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    /**
     * Display a listing of the resource (Public View).
     */
    public function index()
    {
        $events = Event::latest()->get();
        $categories = Event::distinct()->pluck('category');
        return view('gallary.index', compact('events', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('gallary.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'detail' => 'nullable|string',
            'event_date' => 'nullable|date',
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('gallery', 'public');
        }

        Event::create($data);

        return redirect()->route('gallary.index')->with('success', 'Gallery item added successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('gallary.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'detail' => 'nullable|string',
            'event_date' => 'nullable|date',
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            if ($event->photo) {
                Storage::disk('public')->delete($event->photo);
            }
            $data['photo'] = $request->file('photo')->store('gallery', 'public');
        }

        $event->update($data);

        return redirect()->route('gallary.index')->with('success', 'Gallery item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        if ($event->photo) {
            Storage::disk('public')->delete($event->photo);
        }

        $event->delete();

        return redirect()->route('gallary.index')->with('success', 'Gallery item removed successfully.');
    }
}
