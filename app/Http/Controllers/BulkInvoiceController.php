<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\BulkInvoice;
use App\Models\Fee;
use App\Models\Section;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Auth;

class BulkInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', BulkInvoice::class);

        $sections = Section::all();
        if (session('bulkInvoices'))
            $bulkInvoices = session('bulkInvoices');
        else
            $bulkInvoices = BulkInvoice::with(['fees.student.section'])
                ->latest()
                ->paginate(5);

        return view('bulk-invoices.index', compact('bulkInvoices', 'sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $this->authorize('create', BulkInvoice::class);
        $sections = Section::whereHas('students')->get();
        $months = config('enums.months');
        return view('bulk-invoices.create', compact('sections', 'months'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', BulkInvoice::class);

        $request->validate([
            'title' => 'required|string',
            'month' => 'numeric',
            'year' => 'numeric',
            'amount' => 'required|numeric',
            'due_date' => 'required|date',

        ]);

        $sectionIdsArray = array();
        $sectionIdsArray = $request->section_ids_array;

        $month = $request->month;
        $year  = $request->year;


        DB::beginTransaction();
        try {

            $sections = Section::whereIn('id', $sectionIdsArray)->get();
            $lastInvoice = BulkInvoice::where('year', $year)
                ->lockForUpdate()
                ->latest('id')
                ->first();

            $nextNumber = $lastInvoice
                ? intval(substr($lastInvoice->invoice_no, -4)) + 1
                : 1;

            $invoiceNo = sprintf('F%02d%d-%02d', $month, $year - 2000, $nextNumber);

            $bulkInvoice = BulkInvoice::create([
                'title' => $request->title,
                'month' => $request->month,
                'year' => $request->year,
                'amount' => $request->amount,
                'due_date' => new \Carbon\Carbon($year, $month, 10),
                'invoice_no' => $invoiceNo,
            ]);

            // generate bulk invoices
            foreach ($sections as $section) {
                foreach ($section->students as $student) {

                    $student->fees()->create([
                        'bulk_invoice_id' => $bulkInvoice->id,
                        'amount' => $request->amount,
                        'status' => 0,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('bulk-invoices.index')->with('success', 'Successfully created');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage());
            // something went wrong
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $sections = Section::all();

        $bulkInvoice = BulkInvoice::findOrFail($id);
        $this->authorize('view', $bulkInvoice);
        $user = Auth::user();
        $sections = $user->accessibleSections();

        if ($sections->count() == 0) {
            abort(403, 'Unauthorized');
        }

        $fees = Fee::where('bulk_invoice_id', $id)
            ->whereHas('student', function ($query) use ($sections) {
                $query->whereIn('section_id', $sections->pluck('id'));
            })
            ->with('student') // optional: eager load student
            ->get()
            ->sortBy('student.rollno'); // order by rollno

        // Calculate payable fee (all fees)
        $totalPayable = Fee::where('bulk_invoice_id', $id)
            ->whereHas('student', function ($query) use ($sections) {
                $query->whereIn('section_id', $sections->pluck('id'));
            })
            ->sum('amount');

        // Calculate paid fee (fees with status = 1)
        $totalPaid = Fee::where('bulk_invoice_id', $id)
            ->where('status', 1)
            ->whereHas('student', function ($query) use ($sections) {
                $query->whereIn('section_id', $sections->pluck('id'));
            })
            ->sum('amount');

        return view('bulk-invoices.show', compact('bulkInvoice', 'fees', 'totalPayable', 'totalPaid'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        DB::beginTransaction();
        try {

            $bulkInvoice = BulkInvoice::find($id);
            $this->authorize('update', $bulkInvoice);

            $bulkInvoice->update([
                'status' => 1,
            ]);

            // transaction lines
            // Debit → Cash / Bank / JazzCash / Easypaisa
            $bulkInvoice->transaction->lines()->create([
                'account_id' => $request->payment_account_id,
                'debit'      => $bulkInvoice->amount,
                'credit'     => 0,
            ]);

            $feeReceivable = Account::where('code', '1005')->first(); // Fee Receivable

            //Cr to fee recievable
            $bulkInvoice->transaction->lines()->create([
                'account_id' => $feeReceivable->id,
                'debit'     => 0,
                'credit'      => $bulkInvoice->amount,
            ]);

            DB::commit();
            return redirect()->route('bulk-invoices.show', $bulkInvoice)->with('success', 'Successfully updated');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage());
            // something went wrong
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $bulkInvoice = BulkInvoice::findOrFail($id);
        $this->authorize('delete', $bulkInvoice);

        try {
            $bulkInvoice->delete();
            return redirect()->route('bulk-invoices.index')->with('success', 'Successfully deleted');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
            // something went wrong
        }
    }
    public function searchById(Request $request)
    {
        $request->validate([
            'invoice_no' => 'required|string',
        ]);
        $bulkInvoices = BulkInvoice::with(['fees.student.section'])
            ->where('invoice_no', $request->invoice_no)
            ->latest()
            ->paginate(5);
        return redirect()->route('bulk-invoices.index')->with('bulkInvoices', $bulkInvoices);
    }
    public function searchByName(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);
        $name = $request->name;
        $bulkInvoices = BulkInvoice::with(['fees.student.section'])
            ->whereHas('fees.student', function ($q) use ($name) {
                $q->where('name', 'like', "%{$name}%");
            })
            ->latest()
            ->paginate(5);
        return redirect()->route('bulk-invoices.index')->with('bulkInvoices', $bulkInvoices);
    }

    public function searchByClass(Request $request)
    {
        $request->validate([
            'section_id' => 'required|numeric',
        ]);
        $name = $request->name;
        $sectionId = $request->section_id;

        $bulkInvoices = BulkInvoice::with(['fees.student.section'])
            ->whereHas('fees.student', function ($q) use ($sectionId) {
                $q->where('section_id', $sectionId);
            })
            ->latest()
            ->paginate(5);
        return redirect()->route('bulk-invoices.index')->with('bulkInvoices', $bulkInvoices);
    }
    public function  print(Request $request)
    {
        $request->validate([
            'invoice_ids' => 'required|array|min:1',
        ]);
        $invoiceIds = array();
        $invoiceIds = $request->invoice_ids;

        $bulkInvoices = BulkInvoice::whereIn('id', $invoiceIds)->get();
        $pdf = PDF::loadview('reports.fee-invoice', compact('bulkInvoices'))->setPaper('a4', 'portrait');
        $pdf->set_option("isPhpEnabled", true);
        $file = "bulkInvoice - " . rand(10, 99) . ".pdf";
        return $pdf->stream($file);
    }
}
