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
        // 'courses' => 'required|array|min:1',
        // 'courses.*.name' => 'string',
        // 'courses.*.price' => 'required|numeric',
        'package_name' => 'required|string',
        'payment_status' => 'required|string|in:Success,Failed,Pending',
        'bank_name' => 'nullable|string|max:255',
        'account_number' => 'nullable|string|max:255',
        'exam_date' => 'required|date',
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
        $SAT_ACT_Course = DB::transaction(function () use ($request, $parentName, $studentName, $totalAmount) {
            // Save student
            $student = Student::create([
                'student_email' => $request->student_email,
                'parent_name' => $parentName,
                'parent_phone' => $request->parent_phone,
                'parent_email' => $request->parent_email,
                'student_name' => $studentName,
                'school' => $request->school,
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'exam_date' => $request->exam_date
            ]);

            // Save SAT/ACT enrollment
            return SAT_ACT_Course::create([
                'parent_firstname' => $request->parent_firstname,
                'parent_lastname' => $request->parent_lastname,
                'parent_phone' => $request->parent_phone,
                'parent_email' => $request->parent_email,
                'student_firstname' => $request->student_firstname,
                'student_lastname' => $request->student_lastname,
                'student_email' => $request->student_email,
                'school' => $request->school,
                'amount' => $totalAmount,
                'courses' => $request->courses,
                'package_name' => $request->package_name,
                'exam_date' => $request->exam_date,
                'student_id' => $student->id
            ]);
        });

        // Send emails
        Mail::to($request->parent_email)->queue(
            new SatActCourseConfirmation(
                $studentName,
                $request->school,
                $request->package_name,
                $totalAmount,
                $request->payment_status,
                $parentName,
                'parent'
            )
        );

        Mail::to($request->student_email)->queue(
            new SatActCourseConfirmation(
                $studentName,
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
}
