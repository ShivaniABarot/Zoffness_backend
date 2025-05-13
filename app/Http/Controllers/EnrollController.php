<?php

namespace App\Http\Controllers;

use App\Models\Enroll;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Mail\EnrollmentConfirmation;
use Illuminate\Support\Facades\Mail;
class EnrollController extends Controller
{
    /**
     * Display the enrollment form view
     *
     * @return View
     */
    public function index()
    {
        $enrollments = Enroll::all();
        return view('inquiry.enroll', compact('enrollments'));
    }

    /**
     * Get enrollment data for DataTables
     *
     * @param Request $request
     * @param DataTables $dataTable
     * @return JsonResponse
     */
    // public function getData(Request $request, DataTables $dataTable): JsonResponse
    // {
    //     $enrolls = Enroll::select([
    //         DB::raw('CONCAT(parent_first_name, " ", parent_last_name) as parent_name'),
    //         DB::raw('CONCAT(student_first_name, " ", student_last_name) as student_name'),
    //         'parent_phone',
    //         'parent_email',
    //         'student_email',
    //         'school',
    //         'total_amount',
    //         'packages',
    //         'id' // Added for security in action links
    //     ]);

    //     return $dataTable->of($enrolls)
    //         ->addColumn('action', function(Enroll $enroll) {
    //             return view('components.actions.enroll-actions', [
    //                 'enroll' => $enroll
    //             ])->render();
    //         })
    //         ->rawColumns(['action'])
    //         ->make(true);
    // }


    // NEW ENROLLMENT API
    public function new_enroll(Request $request)
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
    
        // Save enrollment
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
    
        // Prepare email data
        $studentName = $request->student_first_name . ' ' . $request->student_last_name;
    
        // Send email to parent
        Mail::to($request->parent_email)->send(
            new EnrollmentConfirmation($studentName, $request->packages, $request->school, $request->total_amount, $request->payment_status)
        );
    
        // Send email to student
        Mail::to($request->student_email)->send(
            new EnrollmentConfirmation($studentName, $request->packages, $request->school, $request->total_amount, $request->payment_status)
        );
    
        return response()->json([
            'success' => true,
            'message' => 'Enrollment created successfully',
            'data'    => $enrollment,
            'payment_status' => $request->payment_status
        ], 201);
    }
    
}