<?php

namespace App\Http\Controllers;

use App\Models\SAT_ACT_Course;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class SATACTCourseController extends Controller
{

    public function new_sat_act(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parent_first_name'   => 'required|string',
            'parent_last_name'    => 'required|string',
            'parent_phone'        => 'required|string',
            'parent_email'        => 'required|email',
            'student_first_name'  => 'required|string',
            'student_last_name'   => 'required|string',
            'student_email'       => 'required|email',
            'school'              => 'required|string',
            'total_amount'        => 'required|numeric',
            'packages'            => 'required|string',
            'payment_status'      => 'required|string|in:Success,Failed,Pending'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }
    
        // Save the enrollment
        $enrollment = Enroll::create($request->only([
            'parent_first_name',
            'parent_last_name',
            'parent_phone',
            'parent_email',
            'student_first_name',
            'student_last_name',
            'student_email',
            'school',
            'total_amount',
            'packages'
        ]));
    
        // Optionally, handle payment status (e.g., log or trigger event)
        // For now, include it in response if needed
    
        return response()->json([
            'success' => true,
            'message' => 'Enrollment created successfully',
            'data'    => $enrollment,
            'payment_status' => $request->payment_status
        ], 201);
    }
    
}