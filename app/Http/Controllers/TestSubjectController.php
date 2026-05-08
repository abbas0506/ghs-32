<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Test;
use App\Models\TestSubject;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        //
        $test = Test::findOrFail($id);
        $testSubjects = $test->testSubjects;
        return view('tests.show', compact('test'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $test = Test::findOrFail($id);
        $u = Auth::user();

        if ($u->hasAnyRole(['admin', 'head'])) {
            $sections = Section::join('grades', 'sections.grade_id', '=', 'grades.id')
                ->select('sections.*')
                ->with(['grade.subjects'])
                ->whereHas('students')
                ->orderBy('grades.grade_no')
                ->orderBy('sections.name')
                ->get();
        } else {
            // Teachers only see sections they are assigned to
            $sections = Section::join('grades', 'sections.grade_id', '=', 'grades.id')
                ->select('sections.*')
                ->with(['schedules' => function ($q) use ($u) {
                    $q->where('user_id', $u->id)->with('subject');
                }])
                ->whereHas('schedules', function ($q) use ($u) {
                    $q->where('user_id', $u->id);
                })
                ->orderBy('grades.grade_no')
                ->orderBy('sections.name')
                ->get();

            // Filter subjects to only show what the teacher teaches
            $sections->each(function ($section) {
                $section->display_subjects = $section->schedules->pluck('subject')->unique('id');
            });
        }

        return view('tests.test-subjects.create', compact('test', 'sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $testId)
    {
        $request->validate([
            'section_subjects' => 'required|array|min:1',
        ]);

        $test = Test::findOrFail($testId);
        
        DB::beginTransaction();
        try {
            // Enforcement: Teachers can only select one class at a time
            if (!Auth::user()->hasAnyRole(['admin', 'head'])) {
                if (count($request->section_subjects) > 1) {
                    throw new Exception('As a teacher, you can only add subjects to one class at a time.');
                }
            }

            $addedCount = 0;
            foreach ($request->section_subjects as $sectionId => $subjectIds) {
                // Find all schedules for this section and the selected subjects
                $allocations = \App\Models\Schedule::where('section_id', $sectionId)
                    ->whereIn('subject_id', $subjectIds)
                    ->get();

                foreach ($allocations as $allocation) {
                    // Check if already exists to avoid duplicates
                    $exists = $test->testSubjects()
                        ->where('section_id', $allocation->section_id)
                        ->where('subject_id', $allocation->subject_id)
                        ->where('lecture_no', $allocation->lecture_no)
                        ->exists();

                    if (!$exists) {
                        $test->testSubjects()->create([
                            'section_id' => $allocation->section_id,
                            'lecture_no' => $allocation->lecture_no,
                            'subject_id' => $allocation->subject_id,
                            'user_id' => $allocation->user_id,
                            'max_marks' => $test->max_marks,
                            'test_date' => $test->test_date ?? now(),
                        ]);
                        $addedCount++;
                    }
                }
            }

            if ($addedCount === 0) {
                throw new Exception('No new valid subject allocations were found for the selected sections and subjects.');
            }

            DB::commit();
            return redirect()->route('tests.show', $test)->with('success', $addedCount . ' subjects added to the assessment.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($testId, $id)
    {
        //
        $test = Test::findOrFail($testId);
        $testSubject = TestSubject::findOrFail($id);
        return view('tests.test-subjects.show', compact('test', 'testSubject'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($testId, $id)
    {
        //
        $test = Test::findOrFail($testId);
        $testSubject = TestSubject::findOrFail($id);
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'teacher');
        })->get();
        return view('tests.test-subjects.edit', compact('test', 'testSubject', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $testId, string $id)
    {
        //
        $request->validate([
            'max_marks' => 'required|numeric|min:1',
            'user_id' => 'required',
        ]);

        $test = Test::findOrFail($testId);
        try {


            if ($request->unlock) {
                $test->testSubjects()->findOrFail($id)->update([
                    // 'max_marks' => $request->max_marks,
                    'result_date' => null,
                ]);
            } else {
                $test->testSubjects()->findOrFail($id)->update([
                    'user_id' => $request->user_id,
                    'max_marks' => $request->max_marks,
                ]);
            }
            return redirect()->route('test.test-subjects.index', $test)->with('success', 'Successfully created');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
            // something went wrong
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($testId, string $id)
    {
        //
        $model = TestSubject::findOrFail($id);
        try {
            $model->delete();
            return redirect()->route('tests.show', $testId)->with('success', 'Successfully deleted');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
            // something went wrong
        }
    }


    public function lock(Request $request, $id)
    {
        //
        $testSubject = TestSubject::findOrFail($id);
        try {
            $testSubject->update([
                'result_date' => now(),
            ]);
            return redirect()->back()->with('success', 'Successfully locked');
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage());
        }
    }
    public function unlock(Request $request, $id)
    {
        //
        $testSubject = TestSubject::findOrFail($id);
        try {
            $testSubject->update([
                'result_date' => null,
            ]);
            return redirect()->back()->with('success', 'Successfully unlocked');
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage());
        }
    }
}
