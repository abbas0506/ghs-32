<?php

namespace App\Http\Controllers;

use App\Models\FtfPayment;
use App\Models\FtfVoucher;
use App\Models\Section;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FtfVoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sectionIds = Auth::user()->accessibleSections()->pluck('id');
        $session    = \App\Models\AcademicSession::current();

        $query = FtfVoucher::whereHas('ftfPayments.student', function ($q) use ($sectionIds) {
            $q->whereIn('section_id', $sectionIds);
        });

        if ($session) {
            $query->whereBetween('due_date', [
                $session->start_date->toDateString(),
                $session->end_date->toDateString(),
            ]);
        }

        $feeVouchers = $query->get();

        return view('ftf-vouchers.index', compact('feeVouchers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = Section::where('id', '>', 1)->get();
        return view('ftf-vouchers.create', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'amount' => 'required|numeric',
            'due_date' => 'required|date',
            'section_ids_array' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $year = \Carbon\Carbon::parse($request->due_date)->year;
            $month = \Carbon\Carbon::parse($request->due_date)->month;

            $feeVoucher = FtfVoucher::create([
                'description' => $request->description,
                'amount'      => $request->amount,
                'due_date'    => $request->due_date,
                'year'        => $year,
                'month'       => $month,
            ]);

            $sectionIdsArray = $request->section_ids_array;
            $students = Student::whereIn('section_id', $sectionIdsArray)->get();
            foreach ($students as $student) {
                $feeVoucher->ftfPayments()->create([
                    'student_id' => $student->id,
                ]);
            }

            DB::commit();
            return redirect()->route('ftf-vouchers.index')->with('success', 'Successfully created');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FtfVoucher $ftfVoucher)
    {
        $sectionIds = Auth::user()->accessibleSections()->pluck('id');
        $sections = Section::whereIn('id', $sectionIds)->get();
        $students = Student::whereIn('section_id', $sectionIds)->get();
        return view('ftf-vouchers.show', ['feeVoucher' => $ftfVoucher, 'students' => $students, 'sections' => $sections]);
    }

    public function edit(FtfVoucher $ftfVoucher)
    {
        $sections = Section::where('id', '>', 1)->get();
        $assignedSectionIds = Section::whereHas('students.ftfPayments', function ($query) use ($ftfVoucher) {
            $query->where('ftf_voucher_id', $ftfVoucher->id);
        })->pluck('id')->toArray();

        return view('ftf-vouchers.edit', ['feeVoucher' => $ftfVoucher, 'sections' => $sections, 'assignedSectionIds' => $assignedSectionIds]);
    }

    public function update(Request $request, FtfVoucher $ftfVoucher)
    {
        $request->validate([
            'description' => 'required',
            'due_date' => 'required|date',
            'amount' => 'required|numeric',
            'section_ids_array' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $year = \Carbon\Carbon::parse($request->due_date)->year;
            $month = \Carbon\Carbon::parse($request->due_date)->month;

            $ftfVoucher->update([
                'description' => $request->description,
                'amount' => $request->amount,
                'due_date' => $request->due_date,
                'year' => $year,
                'month' => $month,
            ]);

            $newSectionIds = $request->section_ids_array;

            $currentSectionIds = Section::whereHas('students.ftfPayments', function ($query) use ($ftfVoucher) {
                $query->where('ftf_voucher_id', $ftfVoucher->id);
            })->pluck('id')->toArray();

            $sectionsToAdd = array_diff($newSectionIds, $currentSectionIds);
            foreach ($sectionsToAdd as $sectionId) {
                $students = Student::where('section_id', $sectionId)->get();
                foreach ($students as $student) {
                    $ftfVoucher->ftfPayments()->firstOrCreate([
                        'student_id' => $student->id,
                    ]);
                }
            }

            $sectionsToRemove = array_diff($currentSectionIds, $newSectionIds);
            foreach ($sectionsToRemove as $sectionId) {
                $ftfVoucher->ftfPayments()
                    ->whereNull('payment_date')
                    ->whereHas('student', function ($query) use ($sectionId) {
                        $query->where('section_id', $sectionId);
                    })->delete();
            }

            DB::commit();
            return redirect()->route('ftf-vouchers.show', $ftfVoucher->id)->with('success', 'Successfully updated');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FtfVoucher $ftfVoucher)
    {
        $ftfVoucher->delete();
        return redirect()->route('ftf-vouchers.index')->with('success', 'Successfully deleted');
    }
}
