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

    public function combinedSelector()
    {
        $tests = Test::where('is_open', false)->latest()->get();
        $sections = Section::all();
        return view('reports.combined-selector', compact('tests', 'sections'));
    }

    public function combinedResult(\Illuminate\Http\Request $request)
    {
        $section = Section::findOrFail($request->section_id);
        $tests = Test::whereIn('id', $request->test_ids)
            ->with(['testSubjects' => function ($query) use ($section) {
                $query->where('section_id', $section->id)->with('results');
            }])
            ->get();

        $students = Student::where('section_id', $section->id)->orderBy('rollno')->get();

        $data = $students->map(function ($student) use ($tests) {
            $studentResults = [
                'rollno' => $student->rollno,
                'name' => $student->name,
                'test_marks' => [],
                'obtained' => 0,
                'total' => 0,
            ];

            foreach ($tests as $test) {
                $testObt = 0;
                $testMax = 0;
                $hasResult = false;

                foreach ($test->testSubjects as $ts) {
                    $result = $ts->results->where('student_id', $student->id)->first();
                    if ($result) {
                        $testObt += $result->obtained_marks;
                        $testMax += $ts->max_marks;
                        $hasResult = true;
                    }
                }

                $studentResults['test_marks'][$test->id] = $hasResult ? $testObt : '-';
                $studentResults['obtained'] += $testObt;
                $studentResults['total'] += $testMax;
            }

            $percentage = $studentResults['total'] > 0 ? ($studentResults['obtained'] / $studentResults['total']) * 100 : 0;
            $studentResults['percentage'] = round($percentage, 1);
            
            // Grade Logic
            if ($percentage >= 90) $grade = 'A+';
            elseif ($percentage >= 80) $grade = 'A';
            elseif ($percentage >= 70) $grade = 'B';
            elseif ($percentage >= 60) $grade = 'C';
            elseif ($percentage >= 50) $grade = 'D';
            else $grade = 'F';

            $studentResults['grade'] = $grade;
            $studentResults['status'] = $percentage >= 50 ? 'Pass' : 'Fail';

            return $studentResults;
        });

        $pdf = PDF::loadview('reports.combined-result', compact('tests', 'section', 'data'))->setPaper('a4', 'landscape');
        $pdf->set_option("isPhpEnabled", true);
        return $pdf->stream("Combined-Report-" . $section->name . ".pdf");
    }

    public function combinedReportCards(\Illuminate\Http\Request $request)
    {
        $section = Section::findOrFail($request->section_id);
        $tests = Test::whereIn('id', $request->test_ids)
            ->with(['testSubjects' => function ($query) use ($section) {
                $query->where('section_id', $section->id)->with('results');
            }])
            ->get();

        $students = Student::where('section_id', $section->id)->orderBy('rollno')->get();

        // 1. Calculate Aggregates for Raking
        $aggregates = $students->map(function ($student) use ($tests) {
            $obtained = 0;
            $total = 0;
            foreach ($tests as $test) {
                foreach ($test->testSubjects as $ts) {
                    $result = $ts->results->where('student_id', $student->id)->first();
                    if ($result) {
                        $obtained += $result->obtained_marks;
                        $total += $ts->max_marks;
                    }
                }
            }
            return [
                'student_id' => $student->id,
                'obtained' => $obtained,
                'total' => $total,
            ];
        });

        // 2. Determine Positions (Rank)
        $sorted = $aggregates->sortByDesc('obtained')->values();
        $ranks = [];
        $rank = 1;
        $prevObtained = null;
        foreach ($sorted as $index => $item) {
            if ($prevObtained !== null && $item['obtained'] < $prevObtained) {
                $rank = $index + 1;
            }
            $ranks[$item['student_id']] = $rank;
            $prevObtained = $item['obtained'];
        }

        // 3. Prepare Final Data for View (Subject-wise)
        $subjects = collect();
        foreach ($tests as $t) {
            foreach ($t->testSubjects as $ts) {
                if (!$subjects->has($ts->subject_id)) {
                    $subjects->put($ts->subject_id, $ts->subject);
                }
            }
        }
        $subjects = $subjects->sortBy('name');

        $data = $students->map(function ($student) use ($tests, $ranks, $subjects) {
            $studentData = [
                'student' => $student,
                'subject_results' => [],
                'total_obtained' => 0,
                'total_max' => 0,
                'rank' => $ranks[$student->id],
            ];

            foreach ($subjects as $sid => $subject) {
                $subObtained = 0;
                $subMax = 0;
                $testMarks = [];
                $hasAny = false;

                foreach ($tests as $test) {
                    $ts = $test->testSubjects->where('subject_id', $sid)->first();
                    $marks = '-';
                    if ($ts) {
                        $result = $ts->results->where('student_id', $student->id)->first();
                        if ($result) {
                            $marks = $result->obtained_marks;
                            $subObtained += $marks;
                            $subMax += $ts->max_marks;
                            $hasAny = true;
                        }
                    }
                    $testMarks[$test->id] = $marks;
                }

                if ($hasAny) {
                    $p = $subMax > 0 ? ($subObtained / $subMax) * 100 : 0;
                    
                    if ($p >= 90) $g = 'A+';
                    elseif ($p >= 80) $g = 'A';
                    elseif ($p >= 70) $g = 'B';
                    elseif ($p >= 60) $g = 'C';
                    elseif ($p >= 50) $g = 'D';
                    else $g = 'F';

                    $studentData['subject_results'][] = [
                        'subject' => $subject,
                        'test_marks' => $testMarks,
                        'obtained' => $subObtained,
                        'max' => $subMax,
                        'percentage' => round($p, 1),
                        'grade' => $g,
                        'status' => $p >= 40 ? 'Pass' : 'Fail' // Using 40 as passing threshold for subjects
                    ];

                    $studentData['total_obtained'] += $subObtained;
                    $studentData['total_max'] += $subMax;
                }
            }
            
            $percent = $studentData['total_max'] > 0 ? ($studentData['total_obtained'] / $studentData['total_max']) * 100 : 0;
            $studentData['percentage'] = round($percent, 1);
            
            return $studentData;
        });

        $pdf = PDF::loadview('reports.combined-report-cards', compact('tests', 'section', 'data', 'subjects'))->setPaper('a4', 'portrait');
        $pdf->set_option("isPhpEnabled", true);
        return $pdf->stream("Combined-Report-Cards-" . $section->name . ".pdf");
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
