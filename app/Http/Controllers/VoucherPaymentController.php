<?php

namespace App\Http\Controllers;

use App\Models\FeePayment;
use App\Models\Section;
use App\Models\Student;
use App\Models\FeeVoucher;
use Exception;
use Illuminate\Http\Request;

class VoucherPaymentController extends Controller
{
    public function index($voucherId, $sectionId)
    {
        //
        // $this->authorize('viewAny', FeePayment::class);

        $voucher = FeeVoucher::findOrFail($voucherId);
        $section = Section::findOrFail($sectionId);
        $fees = FeePayment::where('fee_voucher_id', $voucherId)
            ->whereHas('student', function ($query) use ($sectionId) {
                $query->where('section_id', $sectionId);
            })
            ->with('student') // optional: eager load student
            ->get();

        return view('vouchers.payments.index', compact('voucher', 'section', 'fees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($voucherId, $feeId)
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
    public function show($voucherId, $sectionId, $studentId)
    {
        //
        $section = Section::findOrFail($sectionId);
        // $this->authorize('view', $section);

        $voucher = FeeVoucher::findOrFail($voucherId);
        $student = Student::findOrFail($studentId);

        return view('vouchers.payments.show', compact('voucher', 'section', 'student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($voucherId, $sectionId, $feeId)
    {
        //
        $fee = FeePayment::findOrFail($feeId);
        // $this->authorize('update', $fee);

        $voucher = FeeVoucher::findOrFail($voucherId);
        $section = Section::findOrFail($sectionId);
        $student = $fee->student;

        return view('vouchers.payments.edit', compact('voucher', 'section', 'student', 'fee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $voucherId, $sectionId, $feeId)
    {
        //
        $fee = FeePayment::findOrFail($feeId);
        // $this->authorize('update', $fee);

        try {
            $section = Section::findOrFail($sectionId);
            $voucher = FeeVoucher::findOrFail($voucherId);
            
            // If payment_date is provided, use it. Otherwise if status is 1, set to today.
            if ($request->has('payment_date')) {
                $paymentDate = $request->payment_date;
            } else {
                $paymentDate = $request->status == 1 ? now()->toDateString() : null;
            }

            $fee->update([
                'payment_date' => $paymentDate,
            ]);

            return redirect()->route('voucher.section.payments.index', [$voucher, $section])->with('success', 'Successfully updated');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($voucherId, $sectionId, $id)
    {
        //
        $fee = FeePayment::findOrFail($id);
        // $this->authorize('delete', $fee);

        try {
            $fee->delete();
            return redirect()->route('voucher.section.payments.index', [$voucherId, $sectionId])->with('success', 'Successfully deleted');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function import($voucherId, $sectionId)
    {
        $voucher = FeeVoucher::findOrFail($voucherId);
        $section = Section::findOrFail($sectionId);

        // missing students
        $students = Student::where('section_id', $sectionId)
            ->whereDoesntHave('feePayments', function ($query) use ($voucherId) {
                $query->where('fee_voucher_id', $voucherId);
            })
            ->get();
        return view('vouchers.payments.import', compact('voucher', 'section', 'students'));
    }

    public function postImport(Request $request, $voucherId, $sectionId)
    {
        $request->validate([
            'student_ids_array' => 'required|array',
        ]);

        try {
            $voucher = FeeVoucher::findOrFail($voucherId);
            $section = Section::findOrFail($sectionId);

            $studentIdsArray = $request->student_ids_array;
            $students = Student::whereIn('id', $studentIdsArray)->get();

            foreach ($students as $student) {
                $voucher->feePayments()->create([
                    'student_id' => $student->id,
                ]);
            }
            return redirect()->route('voucher.section.payments.index', [$voucherId, $sectionId])->with('success', 'Successfully imported!');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function postClean($voucherId, $sectionId)
    {
        try {
            FeePayment::where('fee_voucher_id', $voucherId)
                ->whereHas('student', function ($query) use ($sectionId) {
                    $query->where('section_id', $sectionId);
                })
                ->delete();
            return redirect()->back()->with('success', 'Successfully cleaned');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
