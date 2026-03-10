<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Section;
use App\Models\Student;
use App\Models\Test;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $students = Student::all();
        $tests = Test::all();
        // get attendances for today with status 1
        $attendances = Attendance::where('date', today())->where('status', 1)->get();
        $maxAttendance = $students->count() > 0 ? $students->count() : 1; // to avoid division by zero
        //get new admission during last 7 days
        $newAdmissions = Student::where('created_at', '>=', now()->subDays(7))->get();

        // maximum attendace during last week
        $maxAttendance = Attendance::where('date', '>=', now()->subDays(7))->where('status', 1)->count();
        // attendance change compared to yesterday
        $yesterdayAttendance = Attendance::where('date', Carbon::yesterday())->where('status', 1)->count();
        $attendanceChange = $yesterdayAttendance > 0 ? round((($attendances->count() - $yesterdayAttendance) / $yesterdayAttendance) * 100, 1) : 0;

        return view('dashboard', compact('students', 'tests', 'attendances', 'newAdmissions', 'maxAttendance', 'attendanceChange'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
