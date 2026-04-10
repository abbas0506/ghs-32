<?php

namespace App\Http\Controllers;

use App\Models\FeePayment;
use App\Models\FeeVoucher;
use App\Models\Section;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FeeVoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get all fee vouchers
        $sectionIds = Auth::user()->accessibleSections()->pluck('id');
        $feeVouchers = FeeVoucher::whereHas('feePayments.student', function ($query) use ($sectionIds) {
            $query->whereIn('section_id', $sectionIds);
        })->get();

        return view('fee-vouchers.index', compact('feeVouchers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $sections = Section::where('id', '>', 1)->get();
        return view('fee-vouchers.create', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

            $feeVoucher = FeeVoucher::create([
                'description' => $request->description,
                'amount' => $request->amount,
                'due_date' => $request->due_date,
                'year' => $year,
                'month' => $month,
            ]);

            $sectionIdsArray = array();
            $sectionIdsArray = $request->section_ids_array;
            // Assign to multiple sections
            $students = Student::whereIn('section_id', $sectionIdsArray)->get();
            foreach ($students as $student) {
                $feeVoucher->feePayments()->create([
                    'student_id' => $student->id,
                ]);
            }

            DB::commit();
            return redirect()->route('fee-vouchers.index')->with('success', 'Successfully created');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage());
            // something went wrong
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FeeVoucher $feeVoucher)
    {
        //
        $sectionIds = Auth::user()->accessibleSections()->pluck('id');
        $sections = Section::whereIn('id', $sectionIds)->get();
        $students = Student::whereIn('section_id', $sectionIds)->get();
        return view('fee-vouchers.show', compact('feeVoucher', 'students','sections'));
    }

    public function edit(FeeVoucher $feeVoucher)
    {
        $sections = Section::where('id', '>', 1)->get();
        // Get IDs of sections that are already assigned to this voucher
        $assignedSectionIds = Section::whereHas('students.feePayments', function ($query) use ($feeVoucher) {
            $query->where('fee_voucher_id', $feeVoucher->id);
        })->pluck('id')->toArray();

        return view('fee-vouchers.edit', compact('feeVoucher', 'sections', 'assignedSectionIds'));
    }

    public function update(Request $request, FeeVoucher $feeVoucher)
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

            $feeVoucher->update([
                'description' => $request->description,
                'amount' => $request->amount,
                'due_date' => $request->due_date,
                'year' => $year,
                'month' => $month,
            ]);

            $newSectionIds = $request->section_ids_array;

            // Get current section IDs
            $currentSectionIds = Section::whereHas('students.feePayments', function ($query) use ($feeVoucher) {
                $query->where('fee_voucher_id', $feeVoucher->id);
            })->pluck('id')->toArray();

            // Sections to add
            $sectionsToAdd = array_diff($newSectionIds, $currentSectionIds);
            foreach ($sectionsToAdd as $sectionId) {
                $students = Student::where('section_id', $sectionId)->get();
                foreach ($students as $student) {
                    $feeVoucher->feePayments()->firstOrCreate([
                        'student_id' => $student->id,
                    ]);
                }
            }

            // Sections to remove (only delete unpaid ones)
            $sectionsToRemove = array_diff($currentSectionIds, $newSectionIds);
            foreach ($sectionsToRemove as $sectionId) {
                $feeVoucher->feePayments()
                    ->whereNull('payment_date')
                    ->whereHas('student', function ($query) use ($sectionId) {
                        $query->where('section_id', $sectionId);
                    })->delete();
            }

            DB::commit();
            return redirect()->route('fee-vouchers.show', $feeVoucher)->with('success', 'Successfully updated');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeeVoucher $feeVoucher)
    {
        //
    }
}
