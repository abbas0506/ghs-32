<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Lesson;
use App\Models\LessonCue;
use App\Models\Subject;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $subjects = collect();
        $grades = Grade::all();

        $grade = Grade::find($request->query('grade_id'));

        if ($grade) {
            $subjectIds = $grade->lessons->pluck('subject_id')->unique();
            $subjects = Subject::whereIn('id', $subjectIds)->get();
        }
        return view('lessons.index', compact('grades', 'grade', 'subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $grade = Grade::find($request->query('grade_id'));
        $subjects = collect();
        if ($grade) {
            $subjectIds = $grade->lessons->pluck('subject_id')->unique();
            $subjects = Subject::whereNotIn('id', $subjectIds)->get();
        }
        return view('lessons.create', compact('grade', 'subjects'));
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
        $gradeId = $request->input('grade_id');
        $grade = Grade::find($gradeId);
        try {
            foreach ($subjects as $subject) {
                for ($day = 1; $day <= 72; $day++) {
                    $grade->lessons()->create([
                        'subject_id' => $subject->id,
                        'lesson_no' => $day,
                        'title' => "Blank",
                        'activity' => "",
                        'homework' => "",
                        'remarks' => "",
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('lessons.index', [
                'grade_id' => $gradeId
            ])->with('success', 'Lesson plan created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('lessons.index', [
                'grade_id' => $gradeId
            ])->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Lesson $lesson) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lesson $lesson) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lesson $lesson) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson) {}

    public function  init(Request $request)
    {

        $request->validate([
            'grade_id' => 'required|numeric'
        ]);
        $subjects = collect();
        $grades = Grade::all();

        $grade = Grade::find($request->input('grade_id'));

        if ($grade) {
            if ($grade->lessons->count() == 0) {
                // init lesson plans for all subjects
                DB::beginTransaction();
                try {
                    foreach ($grade->subjects as $subject) {
                        for ($day = 1; $day <= 72; $day++) {
                            $grade->lessons()->create([
                                'subject_id' => $subject->id,
                                'lesson_no' => $day,
                                'title' => "Blank",
                                'activity' => "",
                                'homework' => "",
                                'remarks' => "",
                            ]);
                        }
                    }
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();
                    return redirect()->back()->withErrors($e->getMessage());
                    // something went wrong
                }
            }
            $subjectIds = $grade->lessons->pluck('subject_id')->unique();
            $subjects = Subject::whereIn('id', $subjectIds)->get();
        }
        return redirect()->route('lessons.index', ['grade_id' => $grade->id])->with('success', 'Lesson plan successfully initiated!');
    }
}
