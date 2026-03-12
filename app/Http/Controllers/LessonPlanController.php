<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\LessonPlan;
use App\Models\Subject;
use Illuminate\Http\Request;

class LessonPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $subjects = Subject::all();
        $grades = Grade::all();
        $lessonPlans = LessonPlan::all();
        return view('lesson-plans.index', compact('lessonPlans', 'subjects', 'grades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $subjects = Subject::all();
        $grades = Grade::all();
        return view('lesson-plans.create', compact('subjects', 'grades'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'grade_id' => 'required|exists:grades,id',
            'day_no' => 'required|integer|min:1|max:6',
            'topic' => 'required|string|max:255',
            'objective' => 'nullable|string',
            'activity' => 'nullable|string',
            'homework' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        LessonPlan::create($request->all());
        return redirect()->route('lesson-plans.index')->with('success', 'Lesson plan created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LessonPlan $lessonPlan)
    {
        //
        return view('lesson-plans.show', compact('lessonPlan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LessonPlan $lessonPlan)
    {
        //
        $subjects = Subject::all();
        $grades = Grade::all();
        return view('lesson-plans.edit', compact('lessonPlan', 'subjects', 'grades'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LessonPlan $lessonPlan)
    {
        //
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'grade_id' => 'required|exists:grades,id',
            'day_no' => 'required|integer|min:1|max:6',
            'topic' => 'required|string|max:255',
            'objective' => 'nullable|string',
            'activity' => 'nullable|string',
            'homework' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $lessonPlan->update($request->all());
        return redirect()->route('lesson-plans.index')->with('success', 'Lesson plan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LessonPlan $lessonPlan)
    {
        //
        $lessonPlan->delete();
        return redirect()->route('lesson-plans.index')->with('success', 'Lesson plan deleted successfully.');
    }
}
