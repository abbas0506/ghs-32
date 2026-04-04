<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Lesson;
use App\Models\Subject;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\UrduHelper;

use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

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

        // ── Reshape all Urdu fields on every lesson + cues ──────────────────
        $lessons->each(function ($lesson) {
            $lesson->title     = UrduHelper::reshape($lesson->title);
            $lesson->objective = UrduHelper::reshape($lesson->objective);
            $lesson->activity  = UrduHelper::reshape($lesson->activity);
            $lesson->homework  = UrduHelper::reshape($lesson->homework);
            $lesson->remarks   = UrduHelper::reshape($lesson->remarks);

            $lesson->cues->each(function ($cue) {
                $cue->content = UrduHelper::reshape($cue->content);
            });
        });

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

        $pdf = Pdf::loadView('lesson-plans.pdf', compact('lessons', 'meta'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont'             => 'urdu',
                'isRemoteEnabled'         => false,
                'isPhpEnabled'            => true,
                'fontDir'                 => storage_path('fonts/'),
                'fontCache'               => storage_path('fonts/'),
                'isFontSubsettingEnabled' => true,
            ]);

        $filename = 'lesson-plans-'
            . str($grade->name)->slug() . '-'
            . 'L' . $request->from . '-L' . $request->to
            . '.pdf';

        return $pdf->stream($filename);
    }

    // public function generate()
    // {
    //     // Default configs
    //     $defaultConfig = (new ConfigVariables())->getDefaults();
    //     $fontDirs = $defaultConfig['fontDir'];

    //     $defaultFontConfig = (new FontVariables())->getDefaults();
    //     $fontData = $defaultFontConfig['fontdata'];

    //     // Initialize mPDF
    //     $mpdf = new Mpdf([
    //         'mode' => 'utf-8',
    //         'format' => 'A4',

    //         // 👇 Add custom font path
    //         'fontDir' => array_merge($fontDirs, [
    //             resource_path('fonts'),
    //         ]),

    //         // 👇 Register font
    //         'fontdata' => $fontData + [
    //             'nastaliq' => [
    //                 'R' => 'NotoNastaliqUrdu-Regular.ttf',
    //                 'useOTL' => 0xFF,        // CRITICAL: Joins the letters
    //                 // 'useKashida' => 75,
    //             ],
    //         ],

    //         // 👇 Default font
    //         'default_font' => 'nastaliq',
    //         'mode' => 'utf-8',
    //         'format' => 'A4',
    //         'direction' => 'rtl',
    //     ]);

    //     // Load blade view
    //     $html = view('example')->render();

    //     // Write HTML to PDF
    //     $mpdf->WriteHTML($html);

    //     // Output PDF
    //     return $mpdf->Output('example.pdf', 'I'); // I = open in browser
    // }
}
