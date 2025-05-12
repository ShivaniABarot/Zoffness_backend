<?php

namespace App\Http\Controllers;

use App\Models\SAT_ACT_Course;
use Illuminate\Http\Request;
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
        // Validate input
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
            'payment_status' => 'required|string|in:Success,Failed,Pending'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Calculate total amount
        $totalAmount = collect($request->courses)->sum('price');

        // Save the enrollment
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
            'courses' => $request->courses, // Saved as JSON (make sure model casts this)
            'package_name' => $request->package_name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Register created successfully',
            'data' => $SAT_ACT_Course,
            'payment_status' => $request->payment_status
        ], 201);
    }
}
