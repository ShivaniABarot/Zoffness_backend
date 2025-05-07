<?php

namespace App\Http\Controllers;

use App\Models\Enroll;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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
        // Validate incoming request
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