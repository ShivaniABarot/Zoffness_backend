<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\ExecutiveCoaching;
use App\Mail\ExecutiveCoachingConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
class ExecutiveCoachingController extends Controller
{
    public function index()
    {
        $coaching = ExecutiveCoaching::all();
        return view('inquiry.executive_function', compact('coaching'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parent_first_name' => 'nullable|string|max:255',
            'parent_last_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:20',
            'parent_email' => 'nullable|email|max:255',
            'student_first_name' => 'required|string|max:255',
            'student_last_name' => 'required|string|max:255',
            'student_email' => 'required|email|max:255|unique:students,student_email',
            'school' => 'required|string|max:255',
            'package_type' => 'required|string|max:100',
            'subtotal' => 'required|numeric|min:0',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'exam_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $coaching = DB::transaction(function () use ($request) {
                $parentName = $request->parent_first_name . ' ' . $request->parent_last_name;
                $studentName = $request->student_first_name . ' ' . $request->student_last_name;

                // Create student
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

                \Log::info('Student created', [
                    'email' => $request->student_email,
                    'id' => $student->id
                ]);

                // Create executive coaching entry
                $coaching = ExecutiveCoaching::create([
                    'parent_first_name' => $request->parent_first_name,
                    'parent_last_name' => $request->parent_last_name,
                    'parent_phone' => $request->parent_phone,
                    'parent_email' => $request->parent_email,
                    'student_first_name' => $request->student_first_name,
                    'student_last_name' => $request->student_last_name,
                    'student_email' => $request->student_email,
                    'school' => $request->school,
                    'package_type' => $request->package_type,
                    'subtotal' => $request->subtotal,
                    'exam_date' => $request->exam_date,
                    'student_id' => $student->id
                ]);

                return $coaching;
            });

            $studentName = $request->student_first_name . ' ' . $request->student_last_name;
            $parentName = $request->parent_first_name . ' ' . $request->parent_last_name;

            // Queue emails to parent and student
            Mail::to($request->parent_email)->queue(
                new ExecutiveCoachingConfirmation(
                    $studentName,
                    $request->school,
                    $request->package_type,
                    $request->subtotal,
                    $parentName,
                    'parent'
                )
            );

            Mail::to($request->student_email)->queue(
                new ExecutiveCoachingConfirmation(
                    $studentName,
                    $request->school,
                    $request->package_type,
                    $request->subtotal,
                    $studentName,
                    'student'
                )
            );

            return response()->json([
                'status' => 'success',
                'data' => $coaching
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Failed to create executive coaching or student', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process request: ' . $e->getMessage()
            ], 500);
        }
    }
      

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $coaching = ExecutiveCoaching::find($id);

        if (!$coaching) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $coaching
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $coaching = ExecutiveCoaching::find($id);

        if (!$coaching) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'parent_first_name' => 'sometimes|string|max:255',
            'parent_last_name' => 'sometimes|string|max:255',
            'parent_phone' => 'sometimes|string|max:20',
            'parent_email' => 'sometimes|email|max:255',
            'student_first_name' => 'sometimes|string|max:255',
            'student_last_name' => 'sometimes|string|max:255',
            'student_email' => 'sometimes|email|max:255',
            'school' => 'sometimes|string|max:255',
            'package_type' => 'sometimes|string|max:100',
            'subtotal' => 'sometimes|numeric|min:0',
            'exam_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $coaching->update($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $coaching
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $coaching = ExecutiveCoaching::find($id);

        if (!$coaching) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found'
            ], 404);
        }

        $coaching->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Record deleted successfully'
        ], 200);
    }
}
