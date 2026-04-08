<?php

namespace App\Http\Controllers;

use App\Models\LectureTiming;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Models\Section;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class SectionScheduleController extends Controller
{
    //
    public function index()
    {
        $lectures = LectureTiming::all();
        $sections = Section::all()->sortByDesc('grade'); //get active sections
        return view('schedule.section-wise.index', compact('sections', 'lectures'));
    }

    public function print()
    {

        if (session('section_ids'))
            $sections = Section::whereIn('id', session('section_ids'))->get();
        else
            $sections = Section::all();

        $lectures = LectureTiming::all();
        $pdf = PDF::loadview('schedule.section-wise.pdf', compact('sections', 'lectures'))->setPaper('a4', 'landscape');
        $pdf->set_option("isPhpEnabled", true);
        $file = "schedule_" . today()->format('dmy');
        return $pdf->stream($file);
    }

    public function printPortrait()
    {

        if (session('section_ids'))
            $sections = Section::whereIn('id', session('section_ids'))->get();
        else
            $sections = Section::all();

        $lectures = LectureTiming::all();
        $pdf = PDF::loadview('schedule.section-wise.pdf-portrait', compact('sections', 'lectures'))->setPaper('a4', 'portrait');
        $pdf->set_option("isPhpEnabled", true);
        $file = "schedule_portrait_" . today()->format('dmy');
        return $pdf->stream($file);
    }


    public function clear(Request $request)
    {
        $schedules =  Schedule::all();
        try {
            foreach ($schedules as $schedule)
                $schedule->delete();
            return redirect('head/class-schedule')->with('success', 'Successfuly removed all entries!');
        } catch (Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }
    }

    public function post(Request $request)
    {
        $request->validate([
            'section_ids_array' => 'required',
        ]);


        try {
            $sectionIdsArray = array();
            $sectionIdsArray = $request->section_ids_array;
            session([
                'section_ids' => $sectionIdsArray,
            ]);

            if ($request->layout == 'portrait') {
                return redirect()->route('class-schedule.print-portrait');
            }

            return redirect()->route('class-schedule.print');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
            // something went wrong
        }
    }
}
