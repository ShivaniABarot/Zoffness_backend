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
            'parent_first_name' => 'required|string',
            'parent_last_name' => 'required|string',
            'parent_phone' => 'required|string',
            'parent_email' => 'required|email',
            'student_first_name' => 'required|string',
            'student_last_name' => 'required|string',
            'student_email' => 'required|email',
            'sessions' => 'nullable|numeric',
            'packages' => 'nullable|string',
            'school' => 'nullable|string',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }
    
        try {
            DB::transaction(function () use ($request, &$essay) {
                $parentName = $request->parent_first_name . ' ' . $request->parent_last_name;
                $studentName = $request->student_first_name . ' ' . $request->student_last_name;
    
                // Create or update student
                $student = Student::updateOrCreate(
                    ['student_email' => $request->student_email],
                    [
                        'parent_name' => $parentName,
                        'parent_phone' => $request->parent_phone,
                        'parent_email' => $request->parent_email,
                        'student_name' => $studentName,
                        'school' => $request->school ?? '',
                        'bank_name' => $request->bank_name ?? null,
                        'account_number' => $request->account_number ?? null,
                    ]
                );
    
                // Add student_id to the data
                $data = $request->all();
                $data['student_id'] = $student->id;
    
                // Create essay record
                $essay = CollegeEssays::create($data);
            });
    
            $studentName = $request->student_first_name . ' ' . $request->student_last_name;
            $parentName = $request->parent_first_name . ' ' . $request->parent_last_name;
            $sessions = $request->sessions;
            $packages = $request->packages;
    
            // Email to parent
            Mail::to($request->parent_email)->send(
                new CollegeEssayConfirmation($studentName, $sessions, $packages, $parentName, 'parent')
            );
    
            // Email to student
            Mail::to($request->student_email)->send(
                new CollegeEssayConfirmation($studentName, $sessions, $packages, $studentName, 'student')
            );
    
            return response()->json([
                'status' => true,
                'message' => 'College Essay form submitted successfully.',
                'data' => $essay
            ], 201);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to save form: ' . $e->getMessage()
            ], 500);
        }
    }
    
    
}
