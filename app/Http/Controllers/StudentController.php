<?php

namespace App\Http\Controllers;

use App\Models\FeeType;
use App\Models\Section;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    //
    public function create($sectionId)
    {
        //
        $section = Section::findOrFail($sectionId);
        return view('students.create', compact('section'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $sectionId)
    {
        //
        $validated = $request->validate([
            'name' => 'required',
            'father_name' => 'nullable|string',
            'bform' => 'nullable|string',
            'phone' => 'nullable|string',
            'rollno' => 'required|numeric',

        ]);

        DB::beginTransaction();
        try {
            $section = Section::findOrFail($sectionId);
            $validated['fee'] = ($section->grade_id == 0) ? 0 : 20;
            $student = $section->students()->create($validated);


            DB::commit();
            return redirect()->route('sections.show', $section)->with('success', 'Successfully created');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage());
            // something went wrong
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($sectionId, string $id)
    {
        //
        $section = Section::findOrFail($sectionId);
        $student = Student::findOrFail($id);
        return view('students.show', compact('section', 'student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($sectionId, string $id)
    {
        //
        $section = Section::findOrFail($sectionId);
        $student = Student::findOrFail($id);
        return view('students.edit', compact('section', 'student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Section $section, Student $student)
    {
        //
        $validated = $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'name' => 'required|string|max:50',
            'father_name' => 'required|string|max:50',
            'dob' => 'nullable|date',
            'bform' => 'nullable|string|max:15|unique:students,bform,' . $student->id,
            'phone' => 'nullable|string|max:16',
            'address' => 'nullable|string|max:100',
            'rollno' => 'integer|min:1',
            'fee' => 'integer|min:0',
        ]);

        try {

            // ✅ If new photo uploaded
            if ($request->hasFile('photo')) {
                if ($student->photo && Storage::disk('public')->exists($student->photo)) {
                    Storage::disk('public')->delete($student->photo);
                }

                $filename = uniqid() . '.' . $request->photo->extension();
                $path = $request->photo->storeAs('uploads', $filename, 'public');

                $validated['photo'] = $path; // full path like "uploads/abc.jpg"
            }
            $student->update($validated);
            return redirect()->route('sections.show', $section)->with('success', 'Student successfully updated');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
            // something went wrong
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($sectionId, string $id)
    {
        //
        try {
            $student = Student::findOrFail($id);
            $student->delete();
            return redirect()->route('sections.show', $sectionId)->with('success', 'Successfully deleted!');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
