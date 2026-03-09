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
        // $tests = Test::accessible()->get();
        // $tests = Test::all();
        // // find all test whose status is open and user has access to the test allocations   
        // $tests = $tests->filter(function ($test) {
        //     return $test->is_open && $test->testAllocations()->mine()->exists();
        // });
        $tests = Test::mine()->get();
        return view('tests.index', compact('tests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $this->authorize('create', Test::class);
        $sections = Section::whereHas('students')->get();
        return view('tests.create', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $this->authorize('create', Test::class);
        $request->validate([
            'title' => 'required',
            'max_marks' => 'required|numeric',
            'sections_array' => 'required',
        ]);

        $sectionIdsArray = array();
        $sectionIdsArray = $request->sections_array;

        // $grades = Grade::whereIn('id', $gradeIdsArray)->get();

        DB::beginTransaction();
        try {
            $test = Test::create([
                'title' => $request->title,
                'max_marks' => $request->max_marks,
            ]);
            $sections = Section::whereIn('id', $sectionIdsArray)->get();
            foreach ($sections as $section) {
                foreach ($section->allocations as $allocation) {
                    $testAllocation = $test->testAllocations()->create([
                        'section_id' => $allocation->section_id,
                        'lecture_no' => $allocation->lecture_no,
                        'subject_id' => $allocation->subject_id,
                        'user_id' => $allocation->user_id,
                        'max_marks' => $request->max_marks,
                        'test_date' => now(),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('tests.index')->with('success', 'Successfully created');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage());
            // something went wrong
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

        $sectionIds = $test->testAllocations->pluck('section_id')->unique()->toArray();
        // $sections = Section::whereIn('id', $sectionIds)->get();

        $sections = Auth::user()->accessibleSections()->whereIn('id', $sectionIds);
        // echo $sections->get();
        $testAllocations = $test->testAllocations()->mine()->get();

        // echo $testAllocations;
        return view('tests.show', compact('test', 'sections', 'testAllocations'));
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
