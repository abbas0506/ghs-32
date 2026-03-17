<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\LessonPlan;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LessonPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $subjects = Subject::all();
        $grades = Grade::all();

        $grade = $request->query('grade');
        $subject = $request->query('subject');

        if ($grade && $subject) {
            $lessonPlans = LessonPlan::where('grade_id', $grade)
                ->where('subject_id', $subject)
                ->orderBy('day_no')
                ->get();
        } else {
            $lessonPlans = collect();
            // echo 'No filters applied';
        }
        return view('lesson-plans.index', compact('lessonPlans', 'subjects', 'grades', 'grade', 'subject'));
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
        ]);
        DB::beginTransaction();
        try {
            for ($dayNo = 1; $dayNo <= 72; $dayNo++) {
                LessonPlan::create([
                    'subject_id' => $request->input('subject_id'),
                    'grade_id' => $request->input('grade_id'),
                    'day_no' => $dayNo,
                    'topic' => "Topic $dayNo",
                    'objective' => "Learning objective required",
                    'activity' => "",
                    'homework' => "",
                    'remarks' => "",
                ]);
            }
            DB::commit();
            return redirect()->route('lesson-plans.index', [
                'grade' => $request->input('grade_id'),
                'subject' => $request->input('subject_id'),
            ])->with('success', 'Lesson plan created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('lesson-plans.index')->with('warning', 'Failed to create lesson plans: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(LessonPlan $lessonPlan)
    {
        $lessonPlan->load(['grade', 'subject', 'resources']);

        $prevPlan = LessonPlan::where('grade_id', $lessonPlan->grade_id)
            ->where('subject_id', $lessonPlan->subject_id)
            ->where('day_no', $lessonPlan->day_no - 1)
            ->first();

        $nextPlan = LessonPlan::where('grade_id', $lessonPlan->grade_id)
            ->where('subject_id', $lessonPlan->subject_id)
            ->where('day_no', $lessonPlan->day_no + 1)
            ->first();

        return view('lesson-plans.show', compact('lessonPlan', 'prevPlan', 'nextPlan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LessonPlan $lessonPlan)
    {
        $lessonPlan->load(['grade', 'subject']);

        $prevPlan = LessonPlan::where('grade_id', $lessonPlan->grade_id)
            ->where('subject_id', $lessonPlan->subject_id)
            ->where('day_no', $lessonPlan->day_no - 1)
            ->first();

        $nextPlan = LessonPlan::where('grade_id', $lessonPlan->grade_id)
            ->where('subject_id', $lessonPlan->subject_id)
            ->where('day_no', $lessonPlan->day_no + 1)
            ->first();

        return view('lesson-plans.edit', compact('lessonPlan', 'prevPlan', 'nextPlan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LessonPlan $lessonPlan)
    {
        $request->validate([
            'topic'     => 'required|string|max:255',
            'objective' => 'nullable|string',
            'activity'  => 'nullable|string',
            'homework'  => 'nullable|string',
            'remarks'   => 'nullable|string',
        ]);

        $lessonPlan->update($request->only(['topic', 'objective', 'activity', 'homework', 'remarks']));

        // "Save & Next" goes straight to the next day's edit page
        if ($request->has('_save_and_next')) {
            $nextPlan = LessonPlan::where('grade_id', $lessonPlan->grade_id)
                ->where('subject_id', $lessonPlan->subject_id)
                ->where('day_no', $lessonPlan->day_no + 1)
                ->first();

            if ($nextPlan) {
                return redirect()->route('lesson-plans.edit', $nextPlan->id)
                    ->with('success', 'Day ' . $lessonPlan->day_no . ' saved. Now editing Day ' . $nextPlan->day_no . '.');
            }
        }

        return redirect()->route('lesson-plans.index', [
            'grade'   => $lessonPlan->grade_id,
            'subject' => $lessonPlan->subject_id,
        ])->with('success', 'Day ' . $lessonPlan->day_no . ' lesson plan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LessonPlan $lessonPlan)
    {
        $gradeId   = $lessonPlan->grade_id;
        $subjectId = $lessonPlan->subject_id;
        $lessonPlan->delete();
        return redirect()->route('lesson-plans.index', [
            'grade'   => $gradeId,
            'subject' => $subjectId,
        ])->with('success', 'Lesson plan deleted successfully.');
    }
}
