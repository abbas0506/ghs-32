<?php

namespace App\Http\Controllers;

use App\Models\FtfPayment;
use App\Models\Section;
use App\Models\Student;
use App\Models\FtfVoucher;
use Exception;
use Illuminate\Http\Request;

class FtfPaymentController extends Controller
{
    public function index($voucherId, $sectionId)
    {
        $voucher = FtfVoucher::findOrFail($voucherId);
        $section = Section::findOrFail($sectionId);
        $fees = FtfPayment::where('ftf_voucher_id', $voucherId)
            ->whereHas('student', function ($query) use ($sectionId) {
                $query->where('section_id', $sectionId);
            })
            ->with('student')
            ->get();

        return view('ftf-payments.index', compact('voucher', 'section', 'fees'));
    }

    public function show($voucherId, $sectionId, $studentId)
    {
        $section = Section::findOrFail($sectionId);
        $voucher = FtfVoucher::findOrFail($voucherId);
        $student = Student::findOrFail($studentId);

        return view('ftf-payments.show', compact('voucher', 'section', 'student'));
    }

    public function edit($voucherId, $sectionId, $feeId)
    {
        $fee = FtfPayment::findOrFail($feeId);
        $voucher = FtfVoucher::findOrFail($voucherId);
        $section = Section::findOrFail($sectionId);
        $student = $fee->student;

        return view('ftf-payments.edit', compact('voucher', 'section', 'student', 'fee'));
    }

    public function update(Request $request, $voucherId, $sectionId, $feeId)
    {
        $fee = FtfPayment::findOrFail($feeId);

        try {
            $section = Section::findOrFail($sectionId);
            $voucher = FtfVoucher::findOrFail($voucherId);
            
            if ($request->has('payment_date')) {
                $paymentDate = $request->payment_date;
            } else {
                $paymentDate = $request->status == 1 ? now()->toDateString() : null;
            }

            $fee->update([
                'payment_date' => $paymentDate,
            ]);

            return redirect()->route('ftf-voucher.section.payments.index', [$voucher->id, $section->id])->with('success', 'Successfully updated');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function destroy($voucherId, $sectionId, $id)
    {
        $fee = FtfPayment::findOrFail($id);

        try {
            $fee->delete();
            return redirect()->route('ftf-voucher.section.payments.index', [$voucherId, $sectionId])->with('success', 'Successfully deleted');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function import($voucherId, $sectionId)
    {
        $voucher = FtfVoucher::findOrFail($voucherId);
        $section = Section::findOrFail($sectionId);

        $students = Student::where('section_id', $sectionId)
            ->whereDoesntHave('ftfPayments', function ($query) use ($voucherId) {
                $query->where('ftf_voucher_id', $voucherId);
            })
            ->get();
        return view('ftf-payments.import', compact('voucher', 'section', 'students'));
    }

    public function postImport(Request $request, $voucherId, $sectionId)
    {
        $request->validate([
            'student_ids_array' => 'required|array',
        ]);

        try {
            $voucher = FtfVoucher::findOrFail($voucherId);
            $section = Section::findOrFail($sectionId);

            $studentIdsArray = $request->student_ids_array;
            $students = Student::whereIn('id', $studentIdsArray)->get();

            foreach ($students as $student) {
                $voucher->ftfPayments()->create([
                    'student_id' => $student->id,
                ]);
            }
            return redirect()->route('ftf-voucher.section.payments.index', [$voucherId, $sectionId])->with('success', 'Successfully imported!');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function postClean($voucherId, $sectionId)
    {
        try {
            FtfPayment::where('ftf_voucher_id', $voucherId)
                ->whereHas('student', function ($query) use ($sectionId) {
                    $query->where('section_id', $sectionId);
                })
                ->delete();
            return redirect()->back()->with('success', 'Successfully cleaned');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function updateDirect(Request $request, $id)
    {
        $request->validate([
            'payment_date' => 'required|date',
        ]);
        
        $payment = FtfPayment::findOrFail($id);
        $payment->update([
            'payment_date' => $request->payment_date,
        ]);

        return redirect()->back()->with('success', 'Payment date updated successfully.');
    }

    public function destroyDirect($id)
    {
        $payment = FtfPayment::findOrFail($id);
        try {
            $payment->delete();
            return redirect()->back()->with('success', 'Fee payment deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
