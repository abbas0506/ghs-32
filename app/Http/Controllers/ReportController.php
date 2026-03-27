<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Schedule;
use App\Models\Section;
use App\Models\Student;
use App\Models\Test;
use App\Models\TestSubject;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Exception;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    //
    public function subjectResult($id)
    {
        $testSubject = TestSubject::find($id);

        $pdf = PDF::loadview('reports.subject-result', compact('testSubject'))->setPaper('a4', 'portrait');
        $pdf->set_option("isPhpEnabled", true);
        $file = $testSubject->subject->short_name . "-" . rand(1, 100) . ".pdf";
        return $pdf->stream($file);
    }
    public function sectionResult($testId, $sectionId)
    {
        $test = Test::find($testId);
        $section = Section::find($sectionId);
        $lectureNos =  Schedule::where('section_id', $section->id)->pluck('lecture_no')->unique();
        $allocations = $section->allocations->sortBy('lecture_no');

        $pdf = PDF::loadview('reports.section-result', compact('test', 'section', 'lectureNos', 'allocations'))->setPaper('a4', 'portrait');
        $pdf->set_option("isPhpEnabled", true);
        $file = $section->name . "-" . rand(1, 100) . ".pdf";
        return $pdf->stream($file);
    }
    public function sectionPositions($testId, $sectionId)
    {
        $test = Test::find($testId);
        $section = Section::find($sectionId);

        $section = Section::findOrFail($sectionId);
        $test = Test::findOrFail($testId);

        // calculate test ranking

        $students = Student::with('results.testSubject')
            ->whereHas('results.testSubject', function ($query) use ($testId) {
                $query->where('test_id', $testId);
            })
            ->where('section_id', $sectionId)
            ->get();

        $studentPercentages = $students->map(function ($student) use ($test) {
            // obtained_marks marks, total marks
            $obtained_marks = $student->results->where('testSubject.test_id', $test->id)->sum('obtained_marks');
            $total = $student->results->where('testSubject.test_id', $test->id)->sum('testSubject.max_marks');

            // Avoid division by zero
            $percentage = $total > 0 ? ($obtained_marks / $total) * 100 : 0;

            return [
                'id' => $student->id,
                'rollno' => $student->rollno,
                'name' => $student->name,
                'max_marks' => $total,
                'obtained_marks' => $obtained_marks,
                'percentage' => round($percentage, 0),
            ];
        });

        // Sort by percentage descending
        $sortedPercentages = $studentPercentages->sortByDesc('percentage');

        $sortedResult = collect();
        $i = 0;
        foreach ($sortedPercentages as $sortedPercentage) {
            $sortedResult->push([
                'id' => $sortedPercentage['id'],
                'rollno' => $sortedPercentage['rollno'],
                'name' => $sortedPercentage['name'],
                'max_marks' => $sortedPercentage['max_marks'],
                'obtained_marks' => $sortedPercentage['obtained_marks'],
                'percentage' => $sortedPercentage['percentage'],
                'position' => ++$i,
            ]);
        }

        $pdf = PDF::loadview('reports.section-positions', compact('test', 'section', 'sortedResult'))->setPaper('a4', 'portrait');
        $pdf->set_option("isPhpEnabled", true);
        $file = "positions - " . $section->name . ".pdf";
        return $pdf->stream($file);
    }

    public function reportCards($testId, $sectionId)
    {
        $test = Test::find($testId);
        $section = Section::find($sectionId);
        $students = Student::with('results.testSubject')
            ->whereHas('results.testSubject', function ($query) use ($testId) {
                $query->where('test_id', $testId);
            })
            ->where('section_id', $sectionId)
            ->get();

        $studentPercentages = $students->map(function ($student) {
            $obtained_marks = $student->results->sum('obtained_marks');
            $total = $student->results->sum(function ($result) {
                return $result->testSubject->max_marks;
            });

            // Avoid division by zero
            $percentage = $total > 0 ? ($obtained_marks / $total) * 100 : 0;

            return [
                'id' => $student->id,
                'rollno' => $student->rollno,
                'name' => $student->name,
                'max_marks' => $total,
                'obtained_marks' => $obtained_marks,
                'percentage' => round($percentage, 0),
            ];
        });

        // Sort by percentage descending
        $sortedPercentages = $studentPercentages->sortByDesc('percentage');

        $sortedResult = collect();
        $i = 0;
        foreach ($sortedPercentages as $sortedPercentage) {
            $sortedResult->push([
                'id' => $sortedPercentage['id'],
                'rollno' => $sortedPercentage['rollno'],
                'name' => $sortedPercentage['name'],
                'max_marks' => $sortedPercentage['max_marks'],
                'obtained_marks' => $sortedPercentage['obtained_marks'],
                'percentage' => $sortedPercentage['percentage'],
                'position' => ++$i,
            ]);
        }


        $pdf = PDF::loadview('reports.report-cards', compact('test', 'section', 'sortedResult'))->setPaper('a4', 'portrait');
        $pdf->set_option("isPhpEnabled", true);
        $file = "report-cards" . "-" . rand(1, 100) . ".pdf";
        return $pdf->stream($file);
    }

    public function userCards()
    {

        try {
            if (session('users')) {
                $users = session('users');
                $pdf = PDF::loadview('user-cards.pdf-lg', compact('users'))->setPaper('a4', 'portrait');
                $pdf->set_option("isPhpEnabled", true);
                $file = "cards.pdf";
                return $pdf->stream($file);
            } else {
                echo "Nothing to print";
            }
        } catch (Exception $ex) {
            Log::error('An error occurred: ' . $ex->getMessage(), [
                'file' => $ex->getFile(),
                'line' => $ex->getLine(),
            ]);
        }
    }
}
