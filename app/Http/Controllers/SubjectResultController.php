<?php

namespace App\Http\Controllers;

use App\Models\testSubject;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class SubjectResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function print($id)
    {
        //
        $testSubject = TestSubject::findOrFail($id);
        $pdf = PDF::loadview('shared-pdf.subject-result', compact('testSubject'))->setPaper('a4', 'portrait');
        $pdf->set_option("isPhpEnabled", true);
        $file = "subject result.pdf";
        return $pdf->stream($file);
    }
}
