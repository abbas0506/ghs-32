<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Exception;

class SingleuserScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function print()
    {
        if (session('user_ids'))
            $users = User::whereIn('id', session('user_ids'))->get();
        else
            $users = User::has('schedules')->get()->sortByDesc('bps');;

        $pdf = PDF::loadview('schedule.user-wise.pdf', compact('users'))->setPaper('a4', 'landscape');
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
            ]);
            return redirect()->route('user-schedule.print');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
            // something went wrong
        }
    }
}
