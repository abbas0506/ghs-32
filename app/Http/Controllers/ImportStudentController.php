<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\testSubject;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Result;

class ImportStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        //find all those students who have already been included in allocation result
        $testSubject = TestSubject::findOrFail($id);
        $appearingStudentIds = $testSubject->appearingStudents->pluck('id')->unique()->toArray();
        $missingStudents = Student::where('section_id', $testSubject->section_id)
            ->whereNotIn('id', $appearingStudentIds)
            ->get();

        //send only missing students list that needs to be included for results

        return view('tests.test-subjects.import', compact('testSubject', 'missingStudents'));
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
    public function store(Request $request, $id)
    {
        //
        $request->validate([
            'student_ids_array' => 'required',
        ]);

        $testSubject = TestSubject::findOrFail($id);

        DB::beginTransaction();
        try {
            $studentIdsArray = array();
            $studentIdsArray = $request->student_ids_array;

            foreach ($studentIdsArray as $studentId) {
                Result::create([
                    'student_id' => $studentId,
                    'test_subject_id' => $testSubject->id,
                    'obtained_marks' => 0,
                ]);
            }
            DB::commit();
            return redirect()->route('test.test-subjects.show', [$testSubject->test, $testSubject])->with('success', 'Successfully imported');
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
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
