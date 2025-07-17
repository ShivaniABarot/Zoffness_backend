<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CollegeEssays;
use App\Models\Student;
use Illuminate\Support\Facades\Mail;
use App\Mail\CollegeEssayConfirmation;
use Illuminate\Support\Facades\DB;
class CollegeEssaysController extends Controller
{
    public function index()
    {
        $collegessays = CollegeEssays::all();
        return view('inquiry.college_essays', compact('collegessays'));
    }

    public function college_essays(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parent_first_name' => 'nullable|string|max:255',
            'parent_last_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:255',
            'parent_email' => 'nullable|email|max:255',
            'student_first_name' => 'required|string|max:255',
            'student_last_name' => 'required|string|max:255',
            'student_email' => 'required|email|max:255|unique:students,student_email',
            'sessions' => 'nullable|numeric|min:0',
            'packages' => 'nullable|string|max:255',
            'school' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'exam_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();

        try {
            $essay = DB::transaction(function () use ($validatedData) {
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
                    'account_number' => $validatedData['account_number'] ?? null,
                    'exam_date' => $validatedData['exam_date'] ?? null,
                ]);

                \Log::info('Student created', [
                    'email' => $validatedData['student_email'],
                    'id' => $student->id
                ]);

                // Create essay record
                $essay = CollegeEssays::create([
                    'parent_first_name' => $validatedData['parent_first_name'],
                    'parent_last_name' => $validatedData['parent_last_name'],
                    'parent_phone' => $validatedData['parent_phone'],
                    'parent_email' => $validatedData['parent_email'],
                    'student_first_name' => $validatedData['student_first_name'],
                    'student_last_name' => $validatedData['student_last_name'],
                    'student_email' => $validatedData['student_email'],
                    'sessions' => $validatedData['sessions'] ?? null,
                    'packages' => $validatedData['packages'] ?? null,
                    'school' => $validatedData['school'] ?? null,
                    'bank_name' => $validatedData['bank_name'] ?? null,
                    'account_number' => $validatedData['account_number'] ?? null,
                    'exam_date' => $validatedData['exam_date'] ?? null,
                    'student_id' => $student->id
                ]);

                return $essay;
            });

            $studentName = $validatedData['student_first_name'] . ' ' . $validatedData['student_last_name'];
            $parentName = $validatedData['parent_first_name'] . ' ' . $validatedData['parent_last_name'];
            $sessions = $validatedData['sessions'] ?? null;
            $packages = $validatedData['packages'] ?? null;

            // Queue emails to parent and student
            Mail::to($validatedData['parent_email'])->queue(
                new CollegeEssayConfirmation($studentName, $sessions, $packages, $parentName, 'parent')
            );

            Mail::to($validatedData['student_email'])->queue(
                new CollegeEssayConfirmation($studentName, $sessions, $packages, $studentName, 'student')
            );

            return response()->json([
                'status' => true,
                'message' => 'College essay form submitted successfully.',
                'data' => $essay
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Failed to create college essay or student', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to save form: ' . $e->getMessage()
            ], 500);
        }
    }
    
    
}
