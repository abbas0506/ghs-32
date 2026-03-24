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
        $sectionId = Auth::user()->accessibleSections()->pluck('id');
        $feeVouchers = FeeVoucher::whereHas('feePayments.student', function ($query) use ($sectionId) {
            $query->where('section_id', $sectionId);
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
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FeeVoucher $feeVoucher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FeeVoucher $feeVoucher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeeVoucher $feeVoucher)
    {
        //
    }
}
