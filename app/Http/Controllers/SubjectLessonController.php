<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Lesson;
use App\Models\Subject;
use Exception;
use Illuminate\Http\Request;

class SubjectLessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Grade $grade, Subject $subject)
    {
        //
        $lessons = Lesson::where('grade_id', $grade->id)
            ->where('subject_id', $subject->id)
            ->get();

        return view('lessons.subject-lessons.index', compact('grade', 'subject', 'lessons'));
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
    }

    /**
     * Display the specified resource.
     */
    public function show(Grade $grade, Subject $subject, string $id)
    {
        //
        $lesson = Lesson::find($id);
        $lesson->load(['grade', 'subject', 'cues', 'resources']);

        $prevPlan = Lesson::where('grade_id', $lesson->grade_id)
            ->where('subject_id', $lesson->subject_id)
            ->where('lesson_no', $lesson->lesson_no - 1)
            ->first();

        $nextPlan = Lesson::where('grade_id', $lesson->grade_id)
            ->where('subject_id', $lesson->subject_id)
            ->where('lesson_no', $lesson->lesson_no + 1)
            ->first();

        return view('lessons.subject-lessons.show', compact('lesson', 'prevPlan', 'nextPlan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grade $grade, Subject $subject, string $id)
    {
        //
        $lesson = Lesson::find($id);
        $lesson->load(['grade', 'subject', 'cues', 'resources']);

        $prevPlan = Lesson::where('grade_id', $lesson->grade_id)
            ->where('subject_id', $lesson->subject_id)
            ->where('lesson_no', $lesson->lesson_no - 1)
            ->first();

        $nextPlan = Lesson::where('grade_id', $lesson->grade_id)
            ->where('subject_id', $lesson->subject_id)
            ->where('lesson_no', $lesson->lesson_no + 1)
            ->first();

        return view('lessons.subject-lessons.edit', compact('lesson', 'prevPlan', 'nextPlan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grade $grade, Subject $subject, string $id)
    {
        //
        $request->validate([
            'title'     => 'required|string|max:255',
            'objective' => 'nullable|string',
            'activity'  => 'nullable|string',
            'homework'  => 'nullable|string',
            'remarks'   => 'nullable|string',
            'cues'      => 'required|array|min:1',
        ]);

        // $cues = array();
        // $cues = $request->cues;

        $lesson = Lesson::find($id);
        $lesson->update($request->only(['title', 'objective', 'activity', 'homework', 'remarks']));

        $cues = collect($request->input('cues'))
            ->filter(function ($cue) {
                return !is_null($cue) && trim($cue) !== '';
            })
            ->values(); // reindex

        // remove all existing cues of lesson
        $lesson->cues()->delete();

        //add new clues for the lesson
        foreach ($cues as $cue) {
            $lesson->cues()->create([
                'content' => $cue
            ]);
        }

        // "Save & Next" goes straight to the next day's edit page
        if ($request->has('_save_and_next')) {
            $nextPlan = Lesson::where('grade_id', $lesson->grade_id)
                ->where('subject_id', $lesson->subject_id)
                ->where('lesson_no', $lesson->lesson_no + 1)
                ->first();

            if ($nextPlan) {
                return redirect()->route('grade.subject.lessons.edit', [$grade, $subject, $nextPlan->id])
                    ->with('success', 'Day ' . $lesson->lesson_no . ' saved. Now editing Day ' . $nextPlan->lesson_no . '.');
            }
        }

        return redirect()->route('grade.subject.lessons.index', [$grade, $subject])->with('success', 'Day ' . $lesson->lesson_no . ' lesson plan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grade $grade, Subject $subject, $id)
    {
        //
        $lesson = Lesson::find($id);
        try {
            Lesson::where('grade_id', $grade->id)
                ->where('subject_id', $subject->id)
                ->delete();
            return redirect()->route('lessons.index', ['grade_id' => $grade->id])->with('success', 'Lesson plan deleted successfully.');
        } catch (Exception $ex) {
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }
}
