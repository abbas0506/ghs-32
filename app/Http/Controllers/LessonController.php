<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Lesson;
use App\Models\Subject;
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
        $subjects = Subject::all();
        $grades = Grade::all();

        $grade = $request->query('grade');
        $subject = $request->query('subject');

        if ($grade && $subject) {
            $lessons = Lesson::where('grade_id', $grade)
                ->where('subject_id', $subject)
                ->orderBy('lesson_no')
                ->get();
        } else {
            $lessons = collect();
            // echo 'No filters applied';
        }
        return view('lessons.index', compact('lessons', 'subjects', 'grades', 'grade', 'subject'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $subjects = Subject::all();
        $grades = Grade::all();
        return view('lessons.create', compact('subjects', 'grades'));
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
            for ($day = 1; $day <= 72; $day++) {
                $lesson = Lesson::create([
                    'subject_id' => $request->input('subject_id'),
                    'grade_id' => $request->input('grade_id'),
                    'lesson_no' => $day,
                    'title' => "$day",
                    'activity' => "",
                    'homework' => "",
                    'remarks' => "",
                ]);
            }
            $lesson->objectives()->create([
                'objective' => ''
            ]);
            DB::commit();
            return redirect()->route('lessons.index', [
                'grade' => $request->input('grade_id'),
                'subject' => $request->input('subject_id'),
            ])->with('success', 'Lesson plan created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('lessons.index')->with('warning', 'Failed to create lesson plans: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Lesson $lesson)
    {
        $lesson->load(['grade', 'subject', 'objectives', 'resources']);

        $prevPlan = Lesson::where('grade_id', $lesson->grade_id)
            ->where('subject_id', $lesson->subject_id)
            ->where('lesson_no', $lesson->lesson_no - 1)
            ->first();

        $nextPlan = Lesson::where('grade_id', $lesson->grade_id)
            ->where('subject_id', $lesson->subject_id)
            ->where('lesson_no', $lesson->lesson_no + 1)
            ->first();

        return view('lessons.show', compact('lesson', 'prevPlan', 'nextPlan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lesson $lesson)
    {
        $lesson->load(['grade', 'subject']);

        $prevPlan = Lesson::where('grade_id', $lesson->grade_id)
            ->where('subject_id', $lesson->subject_id)
            ->where('lesson_no', $lesson->lesson_no - 1)
            ->first();

        $nextPlan = Lesson::where('grade_id', $lesson->grade_id)
            ->where('subject_id', $lesson->subject_id)
            ->where('lesson_no', $lesson->lesson_no + 1)
            ->first();

        return view('lessons.edit', compact('Lesson', 'prevPlan', 'nextPlan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lesson $lesson)
    {
        $request->validate([
            'topic'     => 'required|string|max:255',
            'objective' => 'nullable|string',
            'activity'  => 'nullable|string',
            'homework'  => 'nullable|string',
            'remarks'   => 'nullable|string',
        ]);

        $lesson->update($request->only(['topic', 'objective', 'activity', 'homework', 'remarks']));

        // "Save & Next" goes straight to the next day's edit page
        if ($request->has('_save_and_next')) {
            $nextPlan = Lesson::where('grade_id', $lesson->grade_id)
                ->where('subject_id', $lesson->subject_id)
                ->where('lesson_no', $lesson->lesson_no + 1)
                ->first();

            if ($nextPlan) {
                return redirect()->route('lessons.edit', $nextPlan->id)
                    ->with('success', 'Day ' . $lesson->lesson_no . ' saved. Now editing Day ' . $nextPlan->lesson_no . '.');
            }
        }

        return redirect()->route('lessons.index', [
            'grade'   => $lesson->grade_id,
            'subject' => $lesson->subject_id,
        ])->with('success', 'Day ' . $lesson->lesson_no . ' lesson plan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson)
    {
        $gradeId   = $lesson->grade_id;
        $subjectId = $lesson->subject_id;
        $lesson->delete();
        return redirect()->route('lessons.index', [
            'grade'   => $gradeId,
            'subject' => $subjectId,
        ])->with('success', 'Lesson plan deleted successfully.');
    }
}
