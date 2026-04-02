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
    public function testSubjectResult($testSubjectId)
    {
        $testSubject = TestSubject::with(['test', 'section', 'subject', 'results.student'])
            ->findOrFail($testSubjectId);

        $test    = $testSubject->test;
        $section = $testSubject->section;
        $subject = $testSubject->subject;

        // All students in this section, sorted by rollno
        $students = Student::where('section_id', $testSubject->section_id)
            ->orderBy('rollno', 'asc')
            ->get();

        $data = $students->map(function ($student) use ($testSubject) {

            $result = $testSubject->results
                ->where('student_id', $student->id)
                ->first();

            $obtained   = $result ? ($result->obtained_marks ?? 0) : 0;
            $max        = $testSubject->max_marks ?? 0;
            $percentage = $max > 0 ? round(($obtained / $max) * 100, 1) : 0;

            // Grade
            if ($percentage >= 90) $grade = 'A+';
            elseif ($percentage >= 80) $grade = 'A';
            elseif ($percentage >= 70) $grade = 'B';
            elseif ($percentage >= 60) $grade = 'C';
            elseif ($percentage >= 50) $grade = 'D';
            else                        $grade = 'F';

            return [
                'rollno'     => $student->rollno,
                'name'       => $student->name,
                'father'     => $student->father_name,
                'obtained'   => $obtained,
                'total'      => $max,
                'percentage' => $percentage,
                'grade'      => $grade,
                'position'   => 0,
            ];
        });

        // ── Ranking (ties handled same as sectionResult) ──────────────────
        $sorted = $data->sortByDesc('obtained')->values();

        $rank      = 1;
        $prevMarks = null;
        $sameRank  = 0;

        foreach ($sorted as $index => $item) {

            if ($prevMarks !== null && $item['obtained'] == $prevMarks) {
                $sameRank++;
            } else {
                $rank     = $index + 1;
                $sameRank = 0;
            }

            $item['position'] = $rank;
            $prevMarks        = $item['obtained'];
            $sorted[$index]   = $item;
        }

        // ── Top 3 (tie-aware) ─────────────────────────────────────────────
        $topStudents = $sorted->filter(fn($item) => $item['position'] <= 3)->values();

        // ── Restore roll-no order for main table ──────────────────────────
        $data = $sorted->sortBy('rollno')->values();

        $pdf = PDF::loadView('reports.subject-result', compact(
            'test',
            'section',
            'subject',
            'testSubject',
            'data',
            'topStudents'
        ))->setPaper('a4', 'portrait');

        $pdf->set_option("isPhpEnabled", true);

        $file = $subject->short_name . "-" . rand(1, 100) . ".pdf";
        return $pdf->stream($file);
    }




    // public function subjectResult($id)
    // {
    //     $testSubject = TestSubject::find($id);

    //     $pdf = PDF::loadview('reports.subject-result', compact('testSubject'))->setPaper('a4', 'portrait');
    //     $pdf->set_option("isPhpEnabled", true);
    //     $file = $testSubject->subject->short_name . "-" . rand(1, 100) . ".pdf";
    //     return $pdf->stream($file);
    // }
    public function sectionResult($testId, $sectionId)
    {
        $test = Test::find($testId);
        $section = Section::find($sectionId);

        $students = Student::where('section_id', $sectionId)
            ->orderBy('rollno', 'asc') // ✅ sorting
            ->get();

        $groupedSubjects = $test->testSubjects
            ->where('section_id', $sectionId) // 🔥 CRITICAL FIX
            ->groupBy('subject_id');

        $subjects = $groupedSubjects->map(function ($items) {
            return $items->first()->subject;
        })->values();

        $subjectMaxMarks = $groupedSubjects->mapWithKeys(function ($items, $subjectId) {
            return [
                $subjectId => $items->sum('max_marks') // total max per subject
            ];
        });

        $data = $students->map(function ($student) use ($groupedSubjects) {

            $row = [
                'rollno' => $student->rollno,
                'name' => $student->name,
                'father' => $student->father_name,
                'subjects' => [],
                'obtained' => 0,
                'total' => 0,
                'percentage' => 0,
                'grade' => '',
                'position' => 0,
            ];

            foreach ($groupedSubjects as $subjectId => $testSubjects) {

                $subjectObt = 0;
                $subjectMax = 0;
                $hasSubject = false; // ✅ KEY FLAG

                foreach ($testSubjects as $testSubject) {

                    $result = $testSubject->results
                        ->where('student_id', $student->id)
                        ->first();

                    if ($result) {
                        $hasSubject = true; // student actually has this subject
                        $subjectObt += $result->obtained_marks ?? 0;
                        $subjectMax += $testSubject->max_marks ?? 0;
                    }
                }

                // store marks (even if 0, for display)
                $row['subjects'][$subjectId] = $subjectObt;

                // ✅ CRITICAL FIX:
                // only add to total if student has this subject
                if ($hasSubject) {
                    $row['obtained'] += $subjectObt;
                    $row['total'] += $subjectMax;
                }
            }

            // ✅ Percentage
            $row['percentage'] = $row['total'] > 0
                ? round(($row['obtained'] / $row['total']) * 100, 1)
                : 0;

            // ✅ Grade
            $p = $row['percentage'];

            if ($p >= 90) $row['grade'] = 'A+';
            elseif ($p >= 80) $row['grade'] = 'A';
            elseif ($p >= 70) $row['grade'] = 'B';
            elseif ($p >= 60) $row['grade'] = 'C';
            elseif ($p >= 50) $row['grade'] = 'D';
            else $row['grade'] = 'F';

            return $row;
        });
        // sort by obtained DESC for ranking
        $sorted = $data->sortByDesc('obtained')->values();

        $rank = 1;
        $prevMarks = null;
        $sameRank = 0;

        foreach ($sorted as $index => $item) {

            if ($prevMarks !== null && $item['obtained'] == $prevMarks) {
                $sameRank++;
            } else {
                $rank = $index + 1;
                $sameRank = 0;
            }

            $item['position'] = $rank;
            $prevMarks = $item['obtained'];

            $sorted[$index] = $item;
        }

        $data = $sorted->sortBy('rollno')->values();

        // Get top 3 position holders
        $topStudents = $sorted->filter(function ($item) {
            return $item['position'] <= 3;
        })->values();

        $pdf = PDF::loadview('reports.section-result', compact('test', 'section', 'data', 'topStudents', 'subjects', 'subjectMaxMarks'))->setPaper('a4', 'portrait');
        $pdf->set_option("isPhpEnabled", true);
        $file = $section->name . "-" . rand(1, 100) . ".pdf";
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
