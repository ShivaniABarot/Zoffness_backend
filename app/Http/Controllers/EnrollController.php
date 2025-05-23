<?php

namespace App\Http\Controllers;

use App\Models\Enroll;
use App\Models\Student;
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
            'parent_first_name' => 'required|string',
            'parent_last_name' => 'required|string',
            'parent_phone' => 'required|string',
            'parent_email' => 'required|email',
            'student_first_name' => 'required|string',
            'student_last_name' => 'required|string',
            'student_email' => 'nullable|email',
            'school' => 'nullable|string',
            'total_amount' => 'required|numeric',
            'packages' => 'required|string',
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

        $parentName = $request->parent_first_name . ' ' . $request->parent_last_name;
        $studentName = $request->student_first_name . ' ' . $request->student_last_name;

        // Use a transaction to ensure data consistency
        try {
            $enrollment = null;
            $student = null;
            DB::transaction(function () use ($request, $parentName, $studentName, &$enrollment, &$student) {
                // Save or update student in students table
                $student = Student::updateOrCreate(
                    ['student_email' => $request->student_email],
                    [
                        'parent_name' => $parentName,
                        'parent_phone' => $request->parent_phone,
                        'parent_email' => $request->parent_email,
                        'student_name' => $studentName,
                        'student_email' => $request->student_email,
                        'school' => $request->school,
                        'bank_name' => $request->bank_name,
                        'account_number' => $request->account_number
                    ]
                );
            
                // Save enrollment with student_id
                $enrollment = Enroll::create(array_merge($request->only([
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
                ]), [
                    'student_id' => $student->id
                ]));
            });

            // Send email to parent
            Mail::to($request->parent_email)->send(
                new EnrollmentConfirmation(
                    $studentName,
                    $request->packages,
                    $request->school,
                    $request->total_amount,
                    $request->payment_status,
                    $parentName,
                    'parent'
                )
            );

            // Send email to student if email is provided
            if ($request->student_email) {
                Mail::to($request->student_email)->send(
                    new EnrollmentConfirmation(
                        $studentName,
                        $request->packages,
                        $request->school,
                        $request->total_amount,
                        $request->payment_status,
                        $studentName,
                        'student'
                    )
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Enrollment and student data created successfully',
                'data' => [
                    'enrollment' => $enrollment,
                    'student' => $student
                ],
                'payment_status' => $request->payment_status
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create enrollment: ' . $e->getMessage()
            ], 500);
        }
    }
}