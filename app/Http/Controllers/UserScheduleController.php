<?php

namespace App\Http\Controllers;

use App\Models\LectureTiming;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Exception;
use Illuminate\Support\Facades\Auth;

class UserScheduleController extends Controller
{
    //
    public function  index()
    {
        //
        $users = User::with('profile')->has('schedules')->get()->sortByDesc('profile.bps'); //get active sections
        $lectures = LectureTiming::all();
        return view('schedule.user-wise.index', compact('users', 'lectures'));
    }

    public function show()
    {
        $schedules = Auth::user()->allocations;
        return view('schedule.show', compact('schedules'));
    }

    public function print()
    {
        // on the basis of print mode, create pdf
        if (session('user_ids'))
            $users = User::whereIn('id', session('user_ids'))->get();
        else
            $users = User::has('schedules')->get()->sortByDesc('bps');;

        $lectures = LectureTiming::all();
        $pdf = PDF::loadview('schedule.user-wise.pdf', compact('users', 'lectures'))->setPaper('a4', 'portrait');
        $pdf->set_option("isPhpEnabled", true);
        $file = "user-schedule.pdf";
        return $pdf->stream($file);
    }

    public function post(Request $request)
    {
        $request->validate([
            'user_ids_array' => 'required',
        ]);
        try {
            $userIdsArray = array();
            $userIdsArray = $request->user_ids_array;
            session([
                'user_ids' => $userIdsArray,
                'print_mode' => $request->print_mode,
            ]);

            return redirect()->route('user-schedule.print');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
            // something went wrong
        }
    }
    public function slips()
    {
        $users = User::has('schedules')->get()->sortByDesc('bps');;

        $lectureTimings = LectureTiming::all();
        $pdf = PDF::loadview('schedule.user-wise.schedule-slips', compact('users', 'lectureTimings'))->setPaper('a4', 'portrait');
        $pdf->set_option("isPhpEnabled", true);
        $file = "schedule-slips.pdf";
        return $pdf->stream($file);
    }
}
