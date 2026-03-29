<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Subject;
use App\Models\Syllabus;
use Exception;
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
    public function create(Request $request)
    {
        //
        // read grade_id from url
        $grade = Grade::find($request->query('grade_id'));
        if (!$grade) {
            abort(404, 'Grade required');
        }
        // find all those subjects which have not associated with this grade
        $alreadyIncludedSubjects = Syllabus::where('grade_id', $grade->id)->pluck('subject_id');
        $subjects = Subject::whereNotIn('id', $alreadyIncludedSubjects)->get();

        return view('syllabus.create', compact('grade', 'subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'grade_id' => 'required|exists:grades,id',
            'subject_ids_array' => 'required|array|min:1'
        ]);

        $subjectIdsArray = collect();
        $subjectIdsArray = $request->subject_ids_array;
        $subjects = Subject::whereIn('id', $subjectIdsArray)->get();
        DB::beginTransaction();
        $grade = Grade::find($request->input('grade_id'));
        try {
            foreach ($subjects as $subject) {
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
            return redirect('syllabi?grade_id=' . $grade->id)->with('warning', 'Failed to create syllabus: ' . $e->getMessage());
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
        return redirect('syllabi?grade_id=' . $syllabus->grade_id)->with('success', 'Syllabus updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Syllabus $syllabus)
    {
        //
        $this->authorize('delete', $syllabus);
        $gradeId = $syllabus->grade_id;
        try {
            $syllabus->delete();
            return redirect()->route('syllabi.index', ['grade_id' => $gradeId])->with('success', 'Successfully deleted');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
            // something went wrong
        }
    }
    public function init(Request $request)
    {
        $request->validate([
            'grade_id' => 'required|exists:grades,id',
        ]);
        DB::beginTransaction();
        $grade = Grade::find($request->input('grade_id'));
        try {
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
            return redirect('syllabi?grade_id=' . $grade->id)->with('warning', 'Failed to create syllabus: ' . $e->getMessage());
        }
    }
}
