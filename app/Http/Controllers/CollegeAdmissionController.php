<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CollegeAdmission;
use App\Models\Student;
use Illuminate\Support\Facades\Mail;
use App\Mail\CollegeAdmissionConfirmation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CollegeAdmissionController extends Controller
{
    public function index()
    {
        $collegeadmission = CollegeAdmission::all();
        return view('inquiry.college_admission', compact('collegeadmission'));
    }
    public function college_admission(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'parent_first_name' => 'required|string|max:255',
            'parent_last_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:255',
            'parent_email' => 'required|email|max:255',
            'student_first_name' => 'required|string|max:255',
            'student_last_name' => 'required|string|max:255',
            'student_email' => 'required|email|max:255|unique:students,student_email',
            'packages' => 'nullable|string|max:255',
            'school' => 'nullable|string|max:255',
            'subtotal' => 'nullable|numeric|min:0',
            'initial_intake' => 'nullable|numeric|min:0',
            'five_session_package' => 'nullable|numeric|min:0',
            'ten_session_package' => 'nullable|numeric|min:0',
            'fifteen_session_package' => 'nullable|numeric|min:0',
            'twenty_session_package' => 'nullable|numeric|min:0',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();

        try {
            $admission = DB::transaction(function () use ($validatedData) {
                $parentName = $validatedData['parent_first_name'] . ' ' . $validatedData['parent_last_name'];
                $studentName = $validatedData['student_first_name'] . ' ' . $validatedData['student_last_name'];

                // Create student
                $student = Student::create([
                    'student_email' => $validatedData['student_email'],
                    'parent_name' => $parentName,
                    'parent_phone' => $validatedData['parent_phone'],
                    'parent_email' => $validatedData['parent_email'],
                    'student_name' => $studentName,
                    'school' => $validatedData['school'] ?? null,
                    'bank_name' => $validatedData['bank_name'] ?? null,
                    'account_number' => $validatedData['account_number'] ?? null
                ]);

                \Log::info('Student created', [
                    'email' => $validatedData['student_email'],
                    'id' => $student->id
                ]);

                // Create college admission record
                $admission = CollegeAdmission::create([
                    'parent_first_name' => $validatedData['parent_first_name'],
                    'parent_last_name' => $validatedData['parent_last_name'],
                    'parent_phone' => $validatedData['parent_phone'],
                    'parent_email' => $validatedData['parent_email'],
                    'student_first_name' => $validatedData['student_first_name'],
                    'student_last_name' => $validatedData['student_last_name'],
                    'student_email' => $validatedData['student_email'],
                    'packages' => $validatedData['packages'] ?? null,
                    'school' => $validatedData['school'] ?? null,
                    'subtotal' => $validatedData['subtotal'] ?? null,
                    'initial_intake' => $validatedData['initial_intake'] ?? null,
                    'five_session_package' => $validatedData['five_session_package'] ?? null,
                    'ten_session_package' => $validatedData['ten_session_package'] ?? null,
                    'fifteen_session_package' => $validatedData['fifteen_session_package'] ?? null,
                    'twenty_session_package' => $validatedData['twenty_session_package'] ?? null,
                    'bank_name' => $validatedData['bank_name'] ?? null,
                    'account_number' => $validatedData['account_number'] ?? null,
                    'student_id' => $student->id
                ]);

                return $admission;
            });

            $studentName = $validatedData['student_first_name'] . ' ' . $validatedData['student_last_name'];
            $parentName = $validatedData['parent_first_name'] . ' ' . $validatedData['parent_last_name'];
            $school = $validatedData['school'] ?? '';
            $subtotal = $validatedData['subtotal'] ?? 0;

            // Queue emails to parent and student
            Mail::to($validatedData['parent_email'])->queue(
                new CollegeAdmissionConfirmation($studentName, $school, $subtotal, $parentName, 'parent')
            );

            Mail::to($validatedData['student_email'])->queue(
                new CollegeAdmissionConfirmation($studentName, $school, $subtotal, $studentName, 'student')
            );

            return response()->json([
                'success' => true,
                'message' => 'College admission record created successfully.',
                'data' => $admission
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Failed to create college admission or student', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to save record: ' . $e->getMessage()
            ], 500);
        }
    }
        
}
