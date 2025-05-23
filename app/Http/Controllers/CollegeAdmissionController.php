<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CollegeAdmission;
use Illuminate\Support\Facades\Mail;
use App\Mail\CollegeAdmissionConfirmation;

class CollegeAdmissionController extends Controller
{
    public function index()
    {
        $collegeadmission = CollegeAdmission::all();
        return view('inquiry.college_admission', compact('collegeadmission'));
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
            'packages' => 'nullable|string', // now expecting a single string
            'school' => 'nullable|string',
            'subtotal' => 'nullable|numeric',
            'initial_intake' => 'nullable|numeric',
            'five_session_package' => 'nullable|numeric',
            'ten_session_package' => 'nullable|numeric',
            'fifteen_session_package' => 'nullable|numeric',
            'twenty_session_package' => 'nullable|numeric',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255'
        ]);
    
        try {
            DB::transaction(function () use ($validatedData, &$admission) {
                $parentName = $validatedData['parent_first_name'] . ' ' . $validatedData['parent_last_name'];
                $studentName = $validatedData['student_first_name'] . ' ' . $validatedData['student_last_name'];
    
                // Create or update student
                $student = Student::updateOrCreate(
                    ['student_email' => $validatedData['student_email']],
                    [
                        'parent_name' => $parentName,
                        'parent_phone' => $validatedData['parent_phone'],
                        'parent_email' => $validatedData['parent_email'],
                        'student_name' => $studentName,
                        'school' => $validatedData['school'] ?? '',
                        'bank_name' => $validatedData['bank_name'] ?? null,
                        'account_number' => $validatedData['account_number'] ?? null
                    ]
                );
    
                // Add student_id to the validated data
                $validatedData['student_id'] = $student->id;
    
                // Create a new admission record
                $admission = CollegeAdmission::create($validatedData);
            });
    
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
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save record: ' . $e->getMessage()
            ], 500);
        }
    }
    
        
}
