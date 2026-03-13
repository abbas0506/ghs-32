<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Section;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Actions\GetAccessibleSections;

class SectionAttendanceController extends Controller
{

    // Oerview all sections attendance
    public function summary()
    {
        $this->authorize('viewSummary', Attendance::class);

        $sections = Auth::user()->accessibleSections();

        $sectionIds = $sections->pluck('id');
        // load data from current date initially
        $date = session('date') ?? now()->toDateString();

        $sections = Section::whereIn('id', $sectionIds)->withCount([
            'students as total' => function ($q) use ($date) {
                $q->whereHas('attendances', function ($q2) use ($date) {
                    $q2->whereDate('date', $date);
                })
                    ->where('status', 1); // active students (optional)
            },

            'students as present' => function ($q) use ($date) {
                $q->whereHas('attendances', function ($q2) use ($date) {
                    $q2->whereDate('date', $date)
                        ->where('status', 1); // present
                })
                    ->where('status', 1); // active students
            },
        ])
            ->has('students')
            ->get();

        $overall_present = Attendance::whereDate('date', $date)->where('status', 1)->count();
        $overall_total = Attendance::whereDate('date', $date)->count();
        return view('attendance.summary', compact('sections', 'date', 'overall_present', 'overall_total'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        //
        $this->authorize('viewAny', Attendance::class);

        $section = Section::findOrFail($id);
        $date = session('date') ?? now()->toDateString();
        $attendances = $section->attendances()->whereDate('date', $date)->get();
        return view('attendance.index', compact('section', 'attendances', 'date'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        //
        $section = Section::findOrFail($id);
        return view('attendance.create', compact('section'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        //
        $request->validate([
            'student_ids_array' => 'required',
        ]);

        $section = Section::findOrFail($id);
        $date = today()->format('Y-m-d');
        DB::beginTransaction();
        try {

            $student_ids_array = $request->student_ids_array;
            $section->students->each(function ($student) use ($student_ids_array, $date) {
                $exists = $student->attendances()
                    ->whereDate('date', $date)
                    ->exists();

                if ($exists) {
                    return back()->with('warning', 'Attendance already marked for this date.');
                }

                $student->attendances()->create([
                    'date' => $date,
                    'status' => in_array($student->id, $student_ids_array),
                ]);
            });
            DB::commit();
            return redirect('attendance/summary');
        } catch (Exception $ex) {
            DB::rollBack();
            return back()->with('warning', $ex->getMessage());
        }
    }

    public function show($id, $attendanceId)
    {
        $attendance = Attendance::find($attendanceId);
        $this->authorize('view', $attendance);

        $section = Section::find($id);
        $student = $attendance->student;
        $date = session('date') ?? now()->toDateString();
        // get all absences of the student before selected date
        $attendances = $student->attendances()->where('status', 0)->whereDate('date', '<=', $date)->get();

        // Get current date
        $currentDate = \Carbon\Carbon::parse($date);

        // Define session start (April 01)
        $sessionStart = $currentDate->month >= 4
            ? \Carbon\Carbon::create($currentDate->year, 4, 1)
            : \Carbon\Carbon::create($currentDate->year - 1, 4, 1);

        // Current month range
        $monthStart = $currentDate->copy()->startOfMonth();
        $monthEnd = $currentDate->copy()->endOfMonth();

        // Absences in current month
        $currentMonthAbsences = $student->attendances()
            ->where('status', 0)
            ->whereDate('date', '>=', $monthStart)
            ->whereDate('date', '<=', $monthEnd)
            ->count();

        // Total working days in current month
        $currentMonthTotal = $student->attendances()
            ->whereDate('date', '>=', $monthStart)
            ->whereDate('date', '<=', $monthEnd)
            ->count();

        // Absences from session start to current date
        $totalAbsencesInPeriod = $student->attendances()
            ->where('status', 0)
            ->whereDate('date', '>=', $sessionStart)
            ->whereDate('date', '<=', $currentDate)
            ->count();

        // Total working days from session start to current date
        $totalDaysInPeriod = $student->attendances()
            ->whereDate('date', '>=', $sessionStart)
            ->whereDate('date', '<=', $currentDate)
            ->count();

        // Calculate rates
        $absenceRateOverall = $totalDaysInPeriod > 0 ? round(($totalAbsencesInPeriod / $totalDaysInPeriod) * 100, 1) : 0;
        $currentMonthRate = $currentMonthTotal > 0 ? round(($currentMonthAbsences / $currentMonthTotal) * 100, 1) : 0;

        // Determine current month trend (rising = up, decreasing = down)
        $currentMonthTrend = $currentMonthRate >= $absenceRateOverall ? 'up' : 'down';

        return view('attendance.show', compact(
            'section',
            'student',
            'attendances',
            'currentMonthAbsences',
            'currentMonthTotal',
            'currentMonthRate',
            'currentMonthTrend',
            'totalAbsencesInPeriod',
            'totalDaysInPeriod',
            'absenceRateOverall',
            'sessionStart',
            'currentDate'
        ));
    }
    /**
     * Display the specified resource.
     */
    public function edit($id, $t)
    {
        //
        $section = Section::findOrFail($id);
        $absence = $section->attendances()->whereDate('date', today())->get();
        $attendances = $section->attendances()->whereDate('date', today())->get();
        return view('attendance.edit', compact('section', 'attendances'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id, $t)
    {
        //
        $request->validate([
            'attendance_ids' => 'required',
            'attendance_ids_checked' => 'required',
        ]);


        $section = Section::findOrFail($id);

        DB::beginTransaction();
        try {
            $attendance_ids = $request->attendance_ids;
            $attendance_ids_checked = $request->attendance_ids_checked;
            $attendances = Attendance::whereIn('id', $attendance_ids)->get();

            $attendances->each(function ($attendance) use ($attendance_ids_checked) {
                $attendance->update([
                    'status' => in_array($attendance->id, $attendance_ids_checked),
                ]);
            });

            DB::commit();
            return redirect()->route('section.attendance.index', $section)->with('success', 'Attendance successfully updated');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage());
            // something went wrong
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }


    // Filter 
    public function filter(Request $request)
    {
        $request->validate([
            'date' => 'required',
        ]);

        session([
            'date' => $request->date,
        ]);
        return  redirect()->route('attendance.summary');
    }
}
