<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Test;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $this->authorize('viewAny', Test::class);
        $tests = Test::mine()->get();
        // find test that have been created this week
        $testsThisWeek = $tests->where('created_at', '>=', now()->subWeek());

        // find % of number of testSubjects that have results submitted for the above tests
        $resultsCount = 0;
        $totalAllocations = 0;
        foreach ($tests as $test) {
            $resultsCount += $test->testSubjects()->mine()->resultSubmitted()->count();
            $totalAllocations += $test->testSubjects()->mine()->count();
        }

        $dataProgress = $totalAllocations > 0 ? round(($resultsCount / $totalAllocations) * 100) : 0;

        return view('tests.index', compact('tests', 'testsThisWeek', 'dataProgress'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Test::class);
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

        return view('tests.create', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Test::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'max_marks' => 'required|numeric|min:1',
            'test_date' => 'required|date',
            'section_subjects' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Enforcement: Teachers can only select one class at a time
            if (!Auth::user()->hasAnyRole(['admin', 'head'])) {
                if (count($request->section_subjects) > 1) {
                    throw new Exception('As a teacher, you can only add subjects to one class at a time.');
                }
            }

            $test = Test::create([
                'title' => $request->title,
                'max_marks' => $request->max_marks,
                'user_id' => !Auth::user()->hasAnyRole(['admin', 'head']) ? Auth::id() : null,
            ]);

            $addedCount = 0;
            foreach ($request->section_subjects as $sectionId => $subjectIds) {
                // Find all schedules for this section and the selected subjects
                $allocations = \App\Models\Schedule::where('section_id', $sectionId)
                    ->whereIn('subject_id', $subjectIds)
                    ->get();

                foreach ($allocations as $allocation) {
                    $test->testSubjects()->create([
                        'section_id' => $allocation->section_id,
                        'lecture_no' => $allocation->lecture_no,
                        'subject_id' => $allocation->subject_id,
                        'user_id' => $allocation->user_id,
                        'max_marks' => $request->max_marks,
                        'test_date' => $request->test_date,
                    ]);
                    $addedCount++;
                }
            }

            if ($addedCount === 0) {
                throw new Exception('No valid subject allocations found for the selected classes and subjects.');
            }

            DB::commit();
            return redirect()->route('tests.show', $test)->with('success', 'Assessment framework generated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $test = Test::findOrFail($id);
        $this->authorize('view', $test);

        $sectionIds = $test->testSubjects->pluck('section_id')->unique()->toArray();
        // $sections = Section::whereIn('id', $sectionIds)->get();

        $sections = Auth::user()->accessibleSections()->whereIn('id', $sectionIds);
        // echo $sections->get();
        $testSubjects = $test->testSubjects()->mine()->get();

        // echo $testSubjects;
        return view('tests.show', compact('test', 'sections', 'testSubjects'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $test = Test::findOrFail($id);
        $this->authorize('update', $test);

        return view('tests.edit', compact('test'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'title' => 'required',
        ]);

        $test = Test::findOrFail($id);
        $this->authorize('update', $test);
        try {
            $test->update($request->all());
            return redirect()->back()->with('success', 'Successfully updated');
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $test = Test::findOrFail($id);
        $this->authorize('update', $test);
        try {
            $test->delete();
            return redirect()->route('tests.index')->with('success', 'Successfully deleted');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
            // something went wrong
        }
    }
    public function lock(Request $request, string $id)
    {
        //
        $test = Test::findOrFail($id);
        $this->authorize('lock', $test);

        try {
            $test->update([
                'is_open' => false,
            ]);
            return redirect()->back()->with('success', 'Successfully locked');
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage());
        }
    }
    public function unlock(Request $request, string $id)
    {
        //
        $test = Test::findOrFail($id);
        $this->authorize('unlock', $test);
        try {
            $test->update([
                'is_open' => true,
            ]);
            return redirect()->back()->with('success', 'Successfully unlocked');
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage());
        }
    }
}
