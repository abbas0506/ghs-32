<?php

namespace App\Http\Controllers;

use App\Models\LectureTiming;
use Illuminate\Http\Request;

class LectureTimingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lectures = LectureTiming::orderBy('lecture_no')->get();
        return view('lecture-timings.index', compact('lectures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lecture-timings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'lecture_no' => 'required|integer|unique:lecture_timings',
            'starts_at' => 'required',
            'duration' => 'required|integer',
        ]);

        LectureTiming::create($request->all());

        return redirect()->route('lecture-timings.index')->with('success', 'Lecture timing created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LectureTiming $lectureTiming)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LectureTiming $lectureTiming)
    {
        return view('lecture-timings.edit', compact('lectureTiming'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LectureTiming $lectureTiming)
    {
        $request->validate([
            'lecture_no' => 'required|integer|unique:lecture_timings,lecture_no,' . $lectureTiming->id,
            'starts_at' => 'required',
            'duration' => 'required|integer',
        ]);

        $lectureTiming->update($request->all());

        return redirect()->route('lecture-timings.index')->with('success', 'Lecture timing updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LectureTiming $lectureTiming)
    {
        $lectureTiming->delete();
        return redirect()->route('lecture-timings.index')->with('success', 'Lecture timing deleted successfully.');
    }
}
