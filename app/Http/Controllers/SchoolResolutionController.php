<?php

namespace App\Http\Controllers;

use App\Models\SchoolResolution;
use Illuminate\Http\Request;

class SchoolResolutionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|string|max:50|unique:school_resolutions,number',
            'date' => 'required|date',
        ]);

        $resolution = SchoolResolution::create([
            'number' => $request->number,
            'date' => $request->date,
        ]);

        return response()->json([
            'success' => true,
            'resolution' => $resolution,
        ]);
    }
}
