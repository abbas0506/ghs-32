<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Subject;
use App\Models\Syllabus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SyllabusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //grades for drop down selection
        $grades = Grade::all();
        $syllabi = collect();

        // read grade_id from url
        $grade = Grade::find($request->query('grade_id'));
        if ($grade) {
            $syllabi = Syllabus::where('grade_id', $grade->id)->get();
        }

        return view('syllabus.index', compact('grade', 'grades', 'syllabi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'grade_id' => 'required|exists:grades,id',
        ]);
        DB::beginTransaction();

        try {
            $grade = Grade::find($request->input('grade_id'));
            foreach ($grade->subjects as $subject) {
                $grade->syllabi()->create([
                    'subject_id' => $subject->id,
                    'term1' => '',
                    'term2' => '',
                    'term3' => '',
                ]);
            }
            DB::commit();
            return redirect()->route('syllabi.index', [
                'grade_id' => $request->input('grade_id'),
            ])->with('success', 'Syllabus created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('syllabi.index')->with('warning', 'Failed to create syllabus: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Syllabus $syllabus)
    {
        //
        return view('syllabus.show', compact('syllabus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Syllabus $syllabus)
    {
        //
        return view('syllabus.edit', compact('syllabus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Syllabus $syllabus)
    {
        //
        $validated = $request->validate([
            'grade_id' => 'required|exists:grades,id',
            'subject_id' => 'required|exists:subjects,id',
            'term1' => 'required',
            'term2' => 'required',
            'term3' => 'required',
        ]);
        $syllabus->update($validated);
        return view('syllabus.edit', compact('syllabus'))->with('success', 'Syllabus updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Syllabus $syllabus)
    {
        //
    }
}
