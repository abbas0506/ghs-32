<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Schedule;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Test;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class SectionResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function print($testId, $sectionId)
    {
        //

        $test = Test::findOrFail($testId);
        $subjectIds = $test->testSubjects
            ->pluck('subject')
            ->unique('id')
            ->values();

        $subjects = Subject::whereIn('id', $subjectIds)->get;

        $test = Test::with([
            'testSubjects.subject',
            'testSubjects.results', // assuming relation
        ])->findOrFail($testId);

        $students = Student::where('section_id', $test->section_id)->get();

        echo $test;

        // $section = Section::findOrFail($sectionId);
        // $lectureNos =  Schedule::where('section_id', $section->id)->pluck('lecture_no')->unique();

        // $allocations = $section->schedules->sortBy('lecture_no');


        // $pdf = PDF::loadview('pdf.section-result', compact('test', 'section', 'lectureNos', 'allocations'))->setPaper('a4', 'portrait');
        // $pdf->set_option("isPhpEnabled", true);
        // $file = "results.pdf";
        // return $pdf->stream($file);
    }
}
