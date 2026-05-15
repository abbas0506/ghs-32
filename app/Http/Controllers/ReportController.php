<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Schedule;
use App\Models\Section;
use App\Models\Student;
use App\Models\Test;
use App\Models\Result;
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

    // Entire Section Result
    public function sectionResult($testId, $sectionId)
    {
        $test = Test::findOrFail($testId);
        $section = Section::findOrFail($sectionId);

        $students = Student::where('section_id', $sectionId)
            ->orderBy('rollno', 'asc')
            ->get();

        // Eager load test subjects for THIS section to define columns/subjects
        $testSubjects = TestSubject::with(['subject'])
            ->where('test_id', $test->id)
            ->where('section_id', $section->id)
            ->get();

        $groupedSubjects = $testSubjects->groupBy('subject_id');

        $subjects = $groupedSubjects->map(function ($items) {
            return $items->first()->subject;
        })->values();

        $subjectMaxMarks = $groupedSubjects->mapWithKeys(function ($items, $subjectId) {
            return [
                $subjectId => $items->sum('max_marks') // total max per subject for this section
            ];
        });

        // Fetch ALL results for these students for this test (handles students who moved sections)
        $studentIds = $students->pluck('id');
        $allResults = Result::whereIn('student_id', $studentIds)
            ->whereHas('testSubject', function ($query) use ($test) {
                $query->where('test_id', $test->id);
            })
            ->with('testSubject')
            ->get();

        $resultsByStudentAndSubject = [];
        foreach ($allResults as $res) {
            if (!isset($resultsByStudentAndSubject[$res->student_id][$res->testSubject->subject_id])) {
                $resultsByStudentAndSubject[$res->student_id][$res->testSubject->subject_id] = [];
            }
            $resultsByStudentAndSubject[$res->student_id][$res->testSubject->subject_id][] = $res;
        }

        $data = $students->map(function ($student) use ($groupedSubjects, $resultsByStudentAndSubject) {

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

            foreach ($groupedSubjects as $subjectId => $subjectTestSubjects) {

                $subjectObt = 0;
                $subjectMax = 0;
                $hasSubject = false;

                if (isset($resultsByStudentAndSubject[$student->id][$subjectId])) {
                    $results = $resultsByStudentAndSubject[$student->id][$subjectId];
                    foreach ($results as $result) {
                        $hasSubject = true;
                        $subjectObt += $result->obtained_marks ?? 0;
                        $subjectMax += $result->testSubject->max_marks ?? 0;
                    }
                }

                // store marks (null if student has no result for this subject)
                $row['subjects'][$subjectId] = $hasSubject ? $subjectObt : null;

                // only add to total if student has this subject
                if ($hasSubject) {
                    $row['obtained'] += $subjectObt;
                    $row['total'] += $subjectMax;
                }
            }

            // Percentage
            $row['percentage'] = $row['total'] > 0
                ? round(($row['obtained'] / $row['total']) * 100, 1)
                : 0;

            // Grade
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
        set_time_limit(120);

        $test = Test::findOrFail($testId);
        $section = Section::findOrFail($sectionId);
        
        // 1. Fetch all students in the section (not just those with results)
        $students = Student::where('section_id', $sectionId)->orderBy('rollno')->get();
        $studentIds = $students->pluck('id');

        // 2. Fetch expected subjects for this section for this test
        $sectionTestSubjects = TestSubject::where('test_id', $testId)
            ->where('section_id', $sectionId)
            ->with('subject')
            ->get();
        
        $testMaxMarks = $sectionTestSubjects->sum('max_marks');

        // 3. Fetch all results for these students and this test
        $allResults = Result::whereIn('student_id', $studentIds)
            ->whereHas('testSubject', function ($query) use ($testId) {
                $query->where('test_id', $testId);
            })
            ->with('testSubject.subject')
            ->get()
            ->groupBy('student_id');

        // 4. Calculate Aggregates for Ranking
        $studentPercentages = $students->map(function ($student) use ($allResults) {
            $obtained = 0;
            $appearedMax = 0;
            if (isset($allResults[$student->id])) {
                $obtained = $allResults[$student->id]->sum('obtained_marks');
                $appearedMax = $allResults[$student->id]->sum(function($res) {
                    return $res->testSubject->max_marks;
                });
            }

            $percentage = $appearedMax > 0 ? ($obtained / $appearedMax) * 100 : 0;

            // Updated Grade Scale
            $percentage = round($percentage, 0);
            if ($percentage >= 80) $grade = 'A+';
            elseif ($percentage >= 70) $grade = 'A';
            elseif ($percentage >= 60) $grade = 'B';
            elseif ($percentage >= 45) $grade = 'C';
            elseif ($percentage >= 33) $grade = 'D';
            else $grade = 'F';

            return [
                'id' => $student->id,
                'rollno' => $student->rollno,
                'name' => $student->name,
                'max_marks' => $appearedMax,
                'obtained_marks' => $obtained,
                'percentage' => $percentage,
            ];
        });

        // Sort by percentage descending
        $sortedResult = $studentPercentages->sortByDesc('percentage')->values()->map(function($item, $index) {
            $item['position'] = $index + 1;
            return $item;
        });

        // 5. Prepare final data for the view
        $data = $students->map(function($student) use ($allResults, $sortedResult, $sectionTestSubjects, $testMaxMarks) {
            $ranking = $sortedResult->where('id', $student->id)->first();
            
            $results = isset($allResults[$student->id]) ? $allResults[$student->id] : collect();
            
            // Map results to subjects (ensuring ONLY subjects with results are shown)
            $subjectResults = $sectionTestSubjects->map(function($ts) use ($results) {
                $res = $results->where('test_subject_id', $ts->id)->first();
                if (!$res) return null; // Filter later

                return [
                    'subject_name' => $ts->subject->name,
                    'max_marks' => $ts->max_marks,
                    'obtained_marks' => $res->obtained_marks,
                    'has_result' => true
                ];
            })->filter(); // Remove nulls

            return [
                'student' => $student,
                'obtained' => $ranking['obtained_marks'],
                'total' => $testMaxMarks,
                'percentage' => $ranking['percentage'],
                'position' => $ranking['position'],
                'subject_results' => $subjectResults,
            ];
        });

        $pdf = PDF::loadview('reports.report-cards', compact('test', 'section', 'data', 'sortedResult'))->setPaper('a4', 'portrait');
        $pdf->set_option("isPhpEnabled", true);
        $file = "report-cards" . "-" . rand(1, 100) . ".pdf";
        return $pdf->stream($file);
    }

    public function combinedSelector()
    {
        $tests = Test::where('is_open', false)->latest()->with('testSubjects')->get()->map(function($test) {
            $test->section_ids = $test->testSubjects->pluck('section_id')->unique()->values();
            return $test;
        });
        $sections = Section::all();
        return view('reports.combined-selector', compact('tests', 'sections'));
    }

    public function combinedResult(\Illuminate\Http\Request $request)
    {
        $section = Section::findOrFail($request->section_id);
        $tests = Test::whereIn('id', $request->test_ids)->get();
        $students = Student::where('section_id', $section->id)->orderBy('rollno')->get();
        $studentIds = $students->pluck('id');

        // 1. Pre-calculate max marks per test for this section (expected subjects)
        $sectionTestSubjects = TestSubject::whereIn('test_id', $request->test_ids)
            ->where('section_id', $section->id)
            ->get()
            ->groupBy('test_id');

        // Attach section-specific total marks to tests for use in the view
        $tests->each(function($test) use ($sectionTestSubjects) {
            $test->section_total_marks = $sectionTestSubjects->has($test->id) 
                ? $sectionTestSubjects->get($test->id)->sum('max_marks') 
                : 0;
        });

        // 2. Fetch all results for these students and these tests (robust against section movement)
        $allResults = Result::whereIn('student_id', $studentIds)
            ->whereHas('testSubject', function ($query) use ($request) {
                $query->whereIn('test_id', $request->test_ids);
            })
            ->with('testSubject')
            ->get();

        $resultsByStudentAndTest = [];
        foreach ($allResults as $res) {
            $resultsByStudentAndTest[$res->student_id][$res->testSubject->test_id][] = $res;
        }

        $data = $students->map(function ($student) use ($tests, $sectionTestSubjects, $resultsByStudentAndTest) {
            $studentResults = [
                'rollno' => $student->rollno,
                'name' => $student->name,
                'test_marks' => [],
                'obtained' => 0,
                'total' => 0,
            ];

            foreach ($tests as $test) {
                $testObt = 0;
                $testAppearedMax = 0;
                $hasResult = false;

                if (isset($resultsByStudentAndTest[$student->id][$test->id])) {
                    foreach ($resultsByStudentAndTest[$student->id][$test->id] as $res) {
                        $testObt += $res->obtained_marks;
                        $testAppearedMax += $res->testSubject->max_marks;
                        $hasResult = true;
                    }
                }

                $studentResults['test_marks'][$test->id] = $hasResult ? $testObt : '-';
                $studentResults['obtained'] += $testObt;
                $studentResults['total'] += $testAppearedMax;
            }

            $percentage = $studentResults['total'] > 0 ? ($studentResults['obtained'] / $studentResults['total']) * 100 : 0;
            $studentResults['percentage'] = round($percentage, 1);

            // Grade Logic (Updated scale)
            if ($percentage >= 80) $grade = 'A+';
            elseif ($percentage >= 70) $grade = 'A';
            elseif ($percentage >= 60) $grade = 'B';
            elseif ($percentage >= 45) $grade = 'C';
            elseif ($percentage >= 33) $grade = 'D';
            else $grade = 'F';

            $studentResults['grade'] = $grade;
            $studentResults['status'] = $percentage >= 50 ? 'Pass' : 'Fail';

            return $studentResults;
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

        // ── Restore roll-no order for main table ──────────────────────────
        $data = $sorted->sortBy('rollno')->values();

        $pdf = PDF::loadview('reports.combined-result', compact('tests', 'section', 'data'))->setPaper('a4', 'landscape');
        $pdf->set_option("isPhpEnabled", true);
        return $pdf->stream("Combined-Report-" . $section->name . ".pdf");
    }

    public function combinedReportCards(\Illuminate\Http\Request $request)
    {
        $section = Section::findOrFail($request->section_id);
        $tests = Test::whereIn('id', $request->test_ids)->get();
        $students = Student::where('section_id', $section->id)->orderBy('rollno')->get();
        $studentIds = $students->pluck('id');

        // 1. Fetch all results for these students and these tests (robust against section movement)
        $allResults = Result::whereIn('student_id', $studentIds)
            ->whereHas('testSubject', function ($query) use ($request) {
                $query->whereIn('test_id', $request->test_ids);
            })
            ->with(['testSubject.subject'])
            ->get();

        $resultsByStudentAndTest = [];
        foreach ($allResults as $res) {
            $resultsByStudentAndTest[$res->student_id][$res->testSubject->test_id][] = $res;
        }

        // 2. Fetch expected subjects for this section across selected tests
        $sectionTestSubjects = TestSubject::whereIn('test_id', $request->test_ids)
            ->where('section_id', $section->id)
            ->with('subject')
            ->get();

        // 3. Collect all subjects that either belong to the section OR have results for these students
        $subjects = $sectionTestSubjects->mapWithKeys(fn($ts) => [$ts->subject_id => $ts->subject]);
        foreach ($allResults as $res) {
            if ($res->testSubject && $res->testSubject->subject) {
                $subjects->put($res->testSubject->subject_id, $res->testSubject->subject);
            }
        }
        $subjects = $subjects->sortBy('name');

        // 4. Calculate Aggregates for Ranking
        $aggregates = $students->map(function ($student) use ($tests, $resultsByStudentAndTest) {
            $obtained = 0;
            $total = 0;
            
            // For ranking, we sum marks of the tests selected
            if (isset($resultsByStudentAndTest[$student->id])) {
                foreach ($resultsByStudentAndTest[$student->id] as $testId => $results) {
                    foreach ($results as $res) {
                        $obtained += $res->obtained_marks;
                        $total += $res->testSubject->max_marks;
                    }
                }
            }

            return [
                'student_id' => $student->id,
                'obtained' => $obtained,
                'total' => $total,
            ];
        });

        // 5. Determine Positions (Rank)
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

        // 6. Prepare Final Data for View
        $data = $students->map(function ($student) use ($tests, $ranks, $subjects, $resultsByStudentAndTest, $sectionTestSubjects) {
            $studentData = [
                'student' => $student,
                'subject_results' => [],
                'test_totals' => [],
                'total_obtained' => 0,
                'total_max' => 0,
                'rank' => $ranks[$student->id] ?? 0,
            ];

            foreach ($subjects as $sid => $subject) {
                $subObtained = 0;
                $subMax = 0;
                $testMarks = [];
                $hasAny = false;

                foreach ($tests as $test) {
                    $marks = '-';
                    if (!isset($studentData['test_totals'][$test->id])) {
                        $studentData['test_totals'][$test->id] = 0;
                    }

                    // Find if student has result for this subject and test
                    if (isset($resultsByStudentAndTest[$student->id][$test->id])) {
                        foreach ($resultsByStudentAndTest[$student->id][$test->id] as $res) {
                            if ($res->testSubject->subject_id == $sid) {
                                if ($marks === '-') $marks = 0;
                                $marks += $res->obtained_marks;
                                $subObtained += $res->obtained_marks;
                                $studentData['test_totals'][$test->id] += $res->obtained_marks;
                                $hasAny = true;
                            }
                        }
                    }
                    
                    // Always add max marks from the section's definition
                    $tsForSection = $sectionTestSubjects->where('test_id', $test->id)->where('subject_id', $sid)->first();
                    if ($tsForSection) {
                        $subMax += $tsForSection->max_marks;
                    }
                    
                    $testMarks[$test->id] = $marks;
                }

                if ($hasAny) {
                    $p = $subMax > 0 ? ($subObtained / $subMax) * 100 : 0;
                    
                    // Updated Grade Scale
                    if ($p >= 80) $g = 'A+';
                    elseif ($p >= 70) $g = 'A';
                    elseif ($p >= 60) $g = 'B';
                    elseif ($p >= 45) $g = 'C';
                    elseif ($p >= 33) $g = 'D';
                    else $g = 'F';

                    $studentData['subject_results'][] = [
                        'subject' => $subject,
                        'test_marks' => $testMarks,
                        'obtained' => $subObtained,
                        'max' => $subMax,
                        'percentage' => round($p, 1),
                        'grade' => $g,
                        'status' => $p >= 33 ? 'Pass' : 'Fail'
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
