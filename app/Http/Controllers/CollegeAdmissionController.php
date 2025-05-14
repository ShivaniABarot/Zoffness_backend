<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CollegeAdmission;
use Illuminate\Support\Facades\Mail;
use App\Mail\CollegeAdmissionConfirmation;

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
                ->editColumn('parent_name', function ($row) {
                    return $row->parent_first_name . ' ' . $row->parent_last_name;
                })
                ->editColumn('student_name', function ($row) {
                    return $row->student_first_name . ' ' . $row->student_last_name;
                })
                ->editColumn('parent_phone', function ($row) {
                    return '<a href="tel:' . $row->parent_phone . '" class="text-decoration-none text-primary">' . $row->parent_phone . '</a>';
                })
                ->editColumn('parent_email', function ($row) {
                    return '<a href="mailto:' . $row->parent_email . '" class="text-decoration-none text-primary">' . $row->parent_email . '</a>';
                })
                ->editColumn('student_email', function ($row) {
                    return $row->student_email
                        ? '<a href="mailto:' . $row->student_email . '" class="text-decoration-none text-primary">' . $row->student_email . '</a>'
                        : '<span class="text-muted">N/A</span>';
                })
                ->editColumn('subtotal', function ($row) {
                    return '$' . number_format($row->subtotal, 2);
                })
                ->rawColumns(['parent_phone', 'parent_email', 'student_email'])
                ->make(true);
        }

        return view('inquiry.college_admission');
    }

    public function collage_addmission(Request $request)
{
    // Validate the incoming request
    $validatedData = $request->validate([
        'parent_first_name' => 'required|string',
        'parent_last_name' => 'required|string',
        'parent_phone' => 'required|string',
        'parent_email' => 'required|email',
        'student_first_name' => 'required|string',
        'student_last_name' => 'required|string',
        'student_email' => 'required|email',
        'packages' => 'required|string', // now expecting a single string
        'school' => 'nullable|string',
        'subtotal' => 'nullable|numeric',
        'initial_intake' => 'nullable|numeric',
        'five_session_package' => 'nullable|numeric',
        'ten_session_package' => 'nullable|numeric',
        'fifteen_session_package' => 'nullable|numeric',
        'twenty_session_package' => 'nullable|numeric',
    ]);

    // No need to encode packages as it's a simple string now

    // Create a new admission record
    $admission = CollegeAdmission::create($validatedData);

    // Prepare data for email
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

    // Return success response
    return response()->json([
        'success' => true,
        'message' => 'College admission record created successfully.',
        'data' => $admission
    ], 201);
}

        
}
