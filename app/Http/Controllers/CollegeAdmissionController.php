<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CollegeAdmission;
use Illuminate\Support\Facades\Mail;
use App\Mail\CollegeAdmissionConfirmation;
use Yajra\DataTables\DataTables;

class CollegeAdmissionController extends Controller
{
    // public function index()
    // {
    //     $collegeadmission = CollegeAdmission::all();
    //     return view('inquiry.college_admission', compact('collegeadmission'));
    // }

    public function index(Request $request)
{
    if ($request->ajax()) {
        $data = CollegeAdmission::select('*');
        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    return view('inquiry.college_admission');
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
    
        $studentName = $validatedData['student_first_name'] . ' ' . $validatedData['student_last_name'];
        $parentName = $validatedData['parent_first_name'] . ' ' . $validatedData['parent_last_name'];
        $school = $validatedData['school'] ?? '';
        $subtotal = $validatedData['subtotal'] ?? 0;
    
        // Email to parent
        Mail::to($validatedData['parent_email'])->send(
            new CollegeAdmissionConfirmation($studentName, $school, $subtotal, $parentName, 'parent')
        );
    
        // Email to student
        Mail::to($validatedData['student_email'])->send(
            new CollegeAdmissionConfirmation($studentName, $school, $subtotal, $studentName, 'student')
        );
    
        return response()->json([
            'success' => true,
            'message' => 'College admission record created successfully.',
            'data' => $admission
        ], 201);
    }
        
}
