<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Subject;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    //
    public function index()
    {
        // $sections = Section::all()->sortBy('grade.grade'); //get active sections
        // return view('allocations.index', compact('sections'));
    }

    // create

    public function create($sectionId, $lecture_no)
    {
        $section = Section::findOrFail($sectionId);
        $subjects = Subject::all();
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'teacher');
        })->get();
        return view('schedule.section-wise.create', compact('section', 'subjects', 'users', 'lecture_no'));
    }

    public function store(Request $request, $sectionId, $lecture_no)
    {
        //
        $request->validate([
            'subject_id' => 'required',
            'user_id' => 'required|numeric'
        ]);

        try {
            Schedule::create([
                'section_id' => $sectionId,
                'lecture_no' => $lecture_no,
                'subject_id' => $request->subject_id,
                'user_id' => $request->user_id,
            ]);
            return redirect('class-schedule')->with('success', 'Successfully created');
        } catch (Exception $e) {
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
    public function edit($sectionId, $lecture_no, $allocation_id)
    {
        //
        $section = Section::findOrFail($sectionId);
        $subjects = Subject::all();
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'teacher');
        })->get();

        $allocation = Schedule::findOrFail($allocation_id);
        return view('schedule.section-wise.edit', compact('allocation', 'subjects', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $sectionId, $lecture_no, $allocationId)
    {
        //
        $request->validate([
            'subject_id' => 'required|numeric',
            'user_id' => 'required|numeric',
        ]);

        $model = Schedule::findOrFail($allocationId);
        try {
            $model->update($request->all());
            return redirect('class-schedule')->with('success', 'Successfully updated');
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($sectionId, $lecture_no, $id)
    {
        //
        $model = Schedule::findOrFail($id);
        try {
            $model->delete();
            return redirect()->route('class-schedule')->with('success', 'Successfully deleted');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
            // something went wrong
        }
    }
}
