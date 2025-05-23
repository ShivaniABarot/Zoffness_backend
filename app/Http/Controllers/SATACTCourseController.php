<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SAT_ACT_Course;
use Illuminate\Http\Request;
use App\Mail\SatActCourseConfirmation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Validator;

class SATACTCourseController extends Controller
{

    public function sat_act_course()
    {
        $sat_act_course = SAT_ACT_Course::with('packages')->get();
        return view('inquiry.sat_act_course', compact('sat_act_course'));
    }

    public function new_sat_act(Request $request)
{
    $validator = Validator::make($request->all(), [
        'parent_firstname' => 'required|string',
        'parent_lastname' => 'required|string',
        'parent_phone' => 'required|string',
        'parent_email' => 'required|email',
        'student_firstname' => 'required|string',
        'student_lastname' => 'required|string',
        'student_email' => 'required|email',
        'school' => 'required|string',
        'courses' => 'array|min:1',
        'courses.*.name' => 'string',
        'courses.*.price' => 'required|numeric',
        'package_name' => 'required|string',
        'payment_status' => 'required|string|in:Success,Failed,Pending',
        'bank_name' => 'nullable|string|max:255',
        'account_number' => 'nullable|string|max:255'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    $totalAmount = collect($request->courses)->sum('price');
    $parentName = $request->parent_firstname . ' ' . $request->parent_lastname;
    $studentName = $request->student_firstname . ' ' . $request->student_lastname;

    try {
        DB::transaction(function () use ($request, $parentName, $studentName, $totalAmount, &$SAT_ACT_Course, &$student) {
            // Save or update student
            $student = Student::updateOrCreate(
                ['student_email' => $request->student_email],
                [
                    'parent_name' => $parentName,
                    'parent_phone' => $request->parent_phone,
                    'parent_email' => $request->parent_email,
                    'student_name' => $studentName,
                    'school' => $request->school,
                    'bank_name' => $request->bank_name,
                    'account_number' => $request->account_number
                ]
            );

            // Save SAT/ACT enrollment
            $SAT_ACT_Course = SAT_ACT_Course::create([
                'parent_firstname' => $request->parent_firstname,
                'parent_lastname' => $request->parent_lastname,
                'parent_phone' => $request->parent_phone,
                'parent_email' => $request->parent_email,
                'student_firstname' => $request->student_firstname,
                'student_lastname' => $request->student_lastname,
                'student_email' => $request->student_email,
                'school' => $request->school,
                'amount' => $totalAmount,
                'courses' => $request->courses, // Should be casted to JSON
                'package_name' => $request->package_name,
                'student_id' => $student->id // if you want to relate
            ]);
        });

        // Send emails
        Mail::to($request->parent_email)->send(
            new SatActCourseConfirmation(
                $studentName,
                $request->courses,
                $request->school,
                $request->package_name,
                $totalAmount,
                $request->payment_status,
                $parentName,
                'parent'
            )
        );

        Mail::to($request->student_email)->send(
            new SatActCourseConfirmation(
                $studentName,
                $request->courses,
                $request->school,
                $request->package_name,
                $totalAmount,
                $request->payment_status,
                $studentName,
                'student'
            )
        );

        return response()->json([
            'success' => true,
            'message' => 'SAT/ACT registration and student data created successfully',
            'data' => $SAT_ACT_Course,
            'payment_status' => $request->payment_status
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to create record: ' . $e->getMessage()
        ], 500);
    }
}

    // public function new_sat_act(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'parent_firstname' => 'required|string',
    //         'parent_lastname' => 'required|string',
    //         'parent_phone' => 'required|string',
    //         'parent_email' => 'required|email',
    //         'student_firstname' => 'required|string',
    //         'student_lastname' => 'required|string',
    //         'student_email' => 'required|email',
    //         'school' => 'required|string',
    //         'courses' => 'array|min:1',
    //         'courses.*.name' => 'string',
    //         'courses.*.price' => 'required|numeric',
    //         'package_name' => 'required|string',
    //         'payment_status' => 'required|string|in:Success,Failed,Pending'
    //     ]);
    
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }
    
    //     // Calculate total amount
    //     $totalAmount = collect($request->courses)->sum('price');
    
    //     // Save the enrollment
    //     $SAT_ACT_Course = SAT_ACT_Course::create([
    //         'parent_firstname' => $request->parent_firstname,
    //         'parent_lastname' => $request->parent_lastname,
    //         'parent_phone' => $request->parent_phone,
    //         'parent_email' => $request->parent_email,
    //         'student_firstname' => $request->student_firstname,
    //         'student_lastname' => $request->student_lastname,
    //         'student_email' => $request->student_email,
    //         'school' => $request->school,
    //         'amount' => $totalAmount,
    //         'courses' => $request->courses, // JSON stored
    //         'package_name' => $request->package_name,
    //     ]);
    
    //     // Prepare data for email
    //     $courses = $request->courses;
    //     $school = $request->school;
    //     $packageName = $request->package_name;
    //     $paymentStatus = $request->payment_status;
    
    //     $studentName = $request->student_firstname . ' ' . $request->student_lastname;
    //     $parentName = $request->parent_firstname . ' ' . $request->parent_lastname;
        
    //     // Send to parent
    //     Mail::to($request->parent_email)->send(
    //         new SatActCourseConfirmation(
    //             $studentName,
    //             $courses,
    //             $school,
    //             $packageName,
    //             $totalAmount,
    //             $paymentStatus,
    //             $parentName,
    //             'parent'
    //         )
    //     );
        
    //     // Send to student
    //     Mail::to($request->student_email)->send(
    //         new SatActCourseConfirmation(
    //             $studentName,
    //             $courses,
    //             $school,
    //             $packageName,
    //             $totalAmount,
    //             $paymentStatus,
    //             $studentName,
    //             'student'
    //         )
    //     );
        
    
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Register created successfully',
    //         'data' => $SAT_ACT_Course,
    //         'payment_status' => $request->payment_status
    //     ], 201);
    // }

}
