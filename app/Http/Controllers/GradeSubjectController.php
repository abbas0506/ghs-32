<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\GradeSubject;
use App\Models\Subject;
use App\Models\Syllabus;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $this->authorize('viewAny', GradeSubject::class);
        $remainingSubjects = collect();
        $gradeSubjects = collect();
        $grade = collect();
        $grades = Grade::all();
        // read grade_id from url
        $grade = Grade::find($request->query('grade_id'));
        if ($grade) {

            $gradeSubjects = GradeSubject::where('grade_id', $grade->id)->get();
            // get remaingin subjects, which have not alredy been selected
            $remainingSubjectsIds = $grade->subjects->pluck('id');
            $remainingSubjects = Subject::whereNotIn('id', $remainingSubjectsIds)->get();
        }
        return view('grade-subjects.index', compact('grade', 'grades', 'remainingSubjects', 'gradeSubjects'));
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
        $this->authorize('create', GradeSubject::class);
        $request->validate([
            'grade_id' => 'required|exists:grades,id',
            'subject_ids_array' => 'required|array|min:1', // must be an array with at least 1 item
        ]);

        $subjectIdsArray = array();
        $subjectIdsArray = $request->subject_ids_array;

        DB::beginTransaction();
        try {
            $grade = Grade::find($request->input('grade_id'));
            foreach ($subjectIdsArray as $subjectId) {
                $grade->gradeSubjects()->create([
                    'subject_id' => $subjectId,
                ]);
            }
            DB::commit();
            return redirect('grade-subjects?grade_id=' . $request->input('grade_id'))->with('success', 'Successfully created');
        } catch (Exception  $ex) {
            DB::rollBack();
            return redirect()->back()->withErrors($ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(GradeSubject $gradeSubject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GradeSubject $gradeSubject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GradeSubject $gradeSubject)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GradeSubject $gradeSubject)
    {
        //
        try {
            $grade = $gradeSubject->grade;
            $gradeSubject->delete();
            return redirect('grade-subjects?grade_id=' . $grade->id)->with('success', 'Successfully deleted');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
            // something went wrong
        }
    }
}
