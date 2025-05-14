<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CollegeEssays;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\CollegeEssayConfirmation;


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
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }
    
        $essay = CollegeEssays::create($request->all());
    
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
    }
    
    
}
