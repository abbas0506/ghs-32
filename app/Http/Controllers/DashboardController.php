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
        $students = Student::all();
        $tests = Test::mine();
        $attendances = Attendance::where('date', today())->where('status', 1)->get();
        $newAdmissions = Student::where('created_at', '>=', now()->subDays(7))->get();
        
        // Latest 7 days attendance summary
        $maxAttendance = Attendance::where('date', '>=', now()->subDays(7))->where('status', 1)->count();
        $highestAttenancePercentage = $students->count() > 0 ? round(($maxAttendance / ($students->count() * 7)) * 100, 1) : 0;

        $tasksDue = collect();
        $pendingTasks = Auth::user()->taskLines()->where('status', 0)->with('task')->get();
        foreach ($pendingTasks as $taskLine) {
            if ($taskLine->task->due_date >= now() && $taskLine->task->due_date <= now()->addDays(7) && !$tasksDue->contains($taskLine->task)) {
                $tasksDue->push($taskLine->task);
            }
        }
        $myAllocationsCount = Auth::user()->schedules()->count();

        // Attendance trends for the last 7 days
        $attendanceTrends = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = Attendance::where('date', $date)->where('status', 1)->count();
            $attendanceTrends[] = [
                'date' => now()->subDays($i)->format('D'),
                'count' => $count
            ];
        }

        $pendingAlumniCount = \App\Models\Alumni::where('is_approved', false)->count();

        return view('dashboard', compact(
            'students', 
            'tests', 
            'attendances', 
            'newAdmissions', 
            'maxAttendance', 
            'highestAttenancePercentage', 
            'tasksDue', 
            'pendingTasks', 
            'myAllocationsCount',
            'attendanceTrends',
            'pendingAlumniCount'
        ));
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
