<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Lesson;
use App\Models\Subject;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class LessonPlanController extends Controller
{
    public function index(Request $request)
    {
        $gradeId = $request->query('grade_id');
        if ($gradeId) {
            $grade = Grade::find($gradeId);
            $subjects = $grade->subjects()->orderBy('name')->get();
        }

        return view('lesson-plans.index', compact('grade', 'subjects'));
    }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'grade_id'    => 'required|integer|exists:grades,id',
            'subject_ids' => 'required|string',
            'from'        => 'required|integer|min:1',
            'to'          => 'required|integer|gte:from',
        ]);

        $subjectIds = explode(',', $request->subject_ids);

        $lessons = Lesson::with(['subject', 'grade', 'resources', 'cues'])
            ->havingGradeId($request->grade_id)
            ->whereIn('subject_id', $subjectIds)
            ->whereBetween('lesson_no', [$request->from, $request->to])
            ->orderBy('lesson_no')
            ->orderBy('subject_id')
            ->get();

        // mPDF handles Urdu shaping natively via lang="ur" in the template.

        // ── Group after reshaping ────────────────────────────────────────────
        $lessons = $lessons->groupBy('lesson_no');

        $grade    = Grade::findOrFail($request->grade_id);
        $subjects = Subject::whereIn('id', $subjectIds)->orderBy('name')->get();

        $meta = [
            'grade'    => $grade,
            'subjects' => $subjects,
            'from'     => $request->from,
            'to'       => $request->to,
            'total'    => $lessons->flatten()->count(),
        ];

        $mpdf = new Mpdf([
            'mode'             => 'utf-8',
            'format'           => 'A4-L',
            'autoScriptToLang' => true,
            'autoLangToFont'   => true,
            'default_font'     => 'dejavusanscondensed',
            'tempDir'          => storage_path('app/mpdf-tmp'),
        ]);

        $html = view('lesson-plans.pdf', compact('lessons', 'meta'))->render();
        $mpdf->WriteHTML($html);

        $filename = 'lesson-plans-'
            . str($grade->name)->slug() . '-'
            . 'L' . $request->from . '-L' . $request->to
            . '.pdf';

        return response($mpdf->Output('', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }


}
