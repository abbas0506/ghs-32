<?php

namespace App\Http\Controllers;

use App\Models\TestSubject;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Result;


class TestSubjectResultController extends Controller
{
    public function index($id)
    {
        //

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
    public function show($testSubjectId, $id)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $testSubject = TestSubject::with('results')->find($id);
        return view('tests.test-subjects.result', compact('testSubject'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //

        $request->validate([
            'max_marks' => 'required|numeric|min:1|max:100',
            'result_ids_array' => 'required',
            'obtained_marks_array' => 'required',
        ]);

        $testSubject = TestSubject::findOrFail($id);

        $resultIdsArray = array();
        $resultIdsArray = $request->result_ids_array;
        $obtained_marksMarksArray = array();
        $obtained_marksMarksArray = $request->obtained_marks_array;

        DB::beginTransaction();
        try {
            $testSubject->update([
                'max_marks' => $request->max_marks,
            ]);
            foreach ($resultIdsArray as $key => $id) {
                $result = Result::findOrFail($id);
                $result->update([
                    'obtained_marks' => $obtained_marksMarksArray[$key],
                ]);
            }
            DB::commit();
            return redirect()->route('test.test-subjects.show', [$testSubject->test, $testSubject])->with('success', 'Successfully saved');
        } catch (Exception $e) {
            db::rollBack();
            return redirect()->back()->withErrors($e->getMessage());
            // something went wrong
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($testSubjectId, $id)
    {
        //

        try {
            $result = Result::findOrFail($id);
            $result->delete();
            return redirect()->back()->with('success', 'Successfully deleted!');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function lock(Request $request, $id)
    {

        $testSubject = TestSubject::findOrFail($id);

        try {
            $testSubject->update([
                'result_date' => now(),
            ]);
            return redirect()->route('test.test.allocations.index', $testSubject->test)->with('success', 'Successfully deleted!');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function unlock(Request $request, $id)
    {

        $testSubject = TestSubject::findOrFail($id);

        try {
            $testSubject->update([
                'result_date' => null,
            ]);
            return redirect()->route('test.allocations.index', $testSubject->test)->with('success', 'Successfully deleted!');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
