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
        //
        $test = Test::findOrFail($id);

        #identify classes/section of this test
        $sectionIds = $test->testSubjects->pluck('section_id')->unique()->toArray();
        $allocations =  Schedule::whereIn('section_id', $sectionIds)->get();


        $unallocated =  Schedule::whereIn('section_id', $sectionIds)
            ->whereNotExists(function ($query) use ($test) {
                $query->select(DB::raw(1))
                    ->from('test_subjects')
                    ->where('test_subjects.test_id', $test->id)
                    ->whereColumn('test_subjects.section_id', 'schedules.section_id')
                    ->whereColumn('test_subjects.lecture_no', 'schedules.lecture_no')
                    ->whereColumn('test_subjects.subject_id', 'schedules.subject_id');
            })
            ->get();

        return view('tests.test-subjects.create', compact('test', 'unallocated'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $testId)
    {
        //
        $request->validate([
            'allocation_id' => 'required|numeric',
        ]);

        $test = Test::findOrFail($testId);
        $allocation =  Schedule::findOrFail($request->allocation_id);
        try {
            $test->testSubjects()->create([
                'section_id' => $allocation->section_id,
                'lecture_no' => $allocation->lecture_no,
                'subject_id' => $allocation->subject_id,
                'user_id' => $allocation->user_id,
                'max_marks' => $test->max_marks,
                'test_date' => $test->test_date,
            ]);
            return redirect()->route('test.test-subjects.index', $test)->with('success', 'Successfully created');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
            // something went wrong
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
