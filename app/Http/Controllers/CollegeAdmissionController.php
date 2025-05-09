<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CollegeAdmission;

class CollegeAdmissionController extends Controller
{
    public function index()
    {
        $collegeadmission = CollegeAdmission::all();
        return view('inquiry.college_admission', compact('collegeadmission'));
    }

    public function collage_addmission(Request $request)
    {
        $validatedData = $request->validate([
            'parent_first_name' => 'required|string',
            'parent_last_name' => 'required|string',
            'parent_phone' => 'required|string',
            'parent_email' => 'required|email',
            'student_first_name' => 'required|string',
            'student_last_name' => 'required|string',
            'student_email' => 'required|email',
            'school' => 'nullable|string',
            'subtotal' => 'nullable|numeric',
            'initial_intake' => 'nullable|numeric',
            'five_session_package' => 'nullable|numeric',
            'ten_session_package' => 'nullable|numeric',
            'fifteen_session_package' => 'nullable|numeric',
            'twenty_session_package' => 'nullable|numeric',
        ]);
        
        $admission = CollegeAdmission::create($validatedData);
    
        return response()->json([
            'success' => true,
            'message' => 'College admission record created successfully.',
            'data' => $admission
        ], 201);
    }
}
