<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CollegeEssays;
use Illuminate\Support\Facades\Validator;


class CollegeEssaysController extends Controller
{
    public function index()
    {
        $collegessays = CollegeEssays::all();
        return view('inquiry.college_essays', compact('collegessays'));
    }

    public function college_essays(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'parent_first_name' => 'required|string',
            'parent_last_name' => 'required|string',
            'parent_phone' => 'required|string',
            'parent_email' => 'required|email',
            'student_first_name' => 'required|string',
            'student_last_name' => 'required|string',
            'student_email' => 'required|email',
            // 'school' => 'nullable|string',
            'sessions' => 'nullable|numeric',
            'packages' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Create new College Essay record
        $essay = CollegeEssays::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'College Essay form submitted successfully.',
            'data' => $essay
        ], 201);
    }
}
