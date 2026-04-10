<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Test;
use App\Models\Schedule;
use App\Models\TestSubject;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClassTestController extends Controller
{
    public function index()
    {
        $tests = Test::where('user_id', Auth::id())
            ->orWhere(function($q) {
                if(Auth::user()->hasAnyRole(['admin', 'head'])) {
                    $q->whereNotNull('user_id');
                } else {
                    $q->where('id', 0); // null set
                }
            })
            ->latest()
            ->get();

        return view('class-tests.index', compact('tests'));
    }

    public function create()
    {
        $u = Auth::user();
        if($u->hasAnyRole(['admin', 'head'])) {
            $allocations = Schedule::with(['section', 'subject', 'user.profile'])->get();
        } else {
            $allocations = Schedule::with(['section', 'subject'])
                ->where('user_id', $u->id)
                ->get();
        }
        
        return view('class-tests.create', compact('allocations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'max_marks' => 'required|numeric|min:1',
            'allocation_id' => 'required|exists:schedules,id',
            'test_date' => 'required|date',
        ]);

        $allocation = Schedule::findOrFail($request->allocation_id);

        DB::beginTransaction();
        try {
            $test = Test::create([
                'title' => $request->title,
                'max_marks' => $request->max_marks,
                'user_id' => Auth::id(),
            ]);

            $test->testSubjects()->create([
                'section_id' => $allocation->section_id,
                'lecture_no' => $allocation->lecture_no,
                'subject_id' => $allocation->subject_id,
                'user_id' => $allocation->user_id,
                'max_marks' => $request->max_marks,
                'test_date' => $request->test_date,
            ]);

            DB::commit();
            return redirect()->route('class-tests.index')->with('success', 'Class test created successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function show($id)
    {
        $test = Test::with('testSubjects.section', 'testSubjects.subject')->findOrFail($id);
        
        // Use existing test subject show logic by finding the single test subject
        $testSubject = $test->testSubjects->first();
        if(!$testSubject) return redirect()->back()->withErrors('Broken test record.');

        return redirect()->route('test.test-subjects.show', [$test, $testSubject]);
    }

    public function analysis(Request $request)
    {
        $allocationId = $request->allocation_id;
        $testIds = $request->test_ids;

        if(!$allocationId) {
             $u = Auth::user();
            if($u->hasAnyRole(['admin', 'head'])) {
                $allocations = Schedule::with(['section', 'subject', 'user.profile'])->get();
                $testSubjects = TestSubject::with('test', 'section', 'subject')->orderBy('test_date', 'desc')->get();
            } else {
                $allocations = Schedule::with(['section', 'subject'])
                    ->where('user_id', $u->id)
                    ->get();
                $testSubjects = TestSubject::with('test', 'section', 'subject')
                    ->where('user_id', $u->id)
                    ->orderBy('test_date', 'desc')
                    ->get();
            }
            return view('class-tests.analysis-selector', compact('allocations', 'testSubjects'));
        }

        $allocation = Schedule::with('section', 'subject', 'user.profile')->findOrFail($allocationId);
        
        // Find tests for this specific allocation
        $query = TestSubject::where('section_id', $allocation->section_id)
            ->where('subject_id', $allocation->subject_id)
            ->where('user_id', $allocation->user_id)
            ->with('test', 'results')
            ->orderBy('test_date', 'asc');

        if($testIds && is_array($testIds)) {
            $query->whereIn('test_id', $testIds);
        }

        $testSubjects = $query->get();

        $students = Student::where('section_id', $allocation->section_id)->orderBy('rollno')->get();

        return view('class-tests.analysis', compact('allocation', 'testSubjects', 'students'));
    }

    public function destroy($id)
    {
        $test = Test::findOrFail($id);
        if($test->user_id != Auth::id() && !Auth::user()->hasAnyRole(['admin', 'head'])) {
            abort(403);
        }
        $test->delete();
        return redirect()->route('class-tests.index')->with('success', 'Deleted successfully.');
    }
}
