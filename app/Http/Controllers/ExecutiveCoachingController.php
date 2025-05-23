<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExecutiveCoaching;
use App\Mail\ExecutiveCoachingConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

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
            'parent_first_name' => 'required|string|max:255',
            'parent_last_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'parent_email' => 'required|email|max:255',
            'student_first_name' => 'required|string|max:255',
            'student_last_name' => 'required|string|max:255',
            'student_email' => 'required|email|max:255',
            'school' => 'required|string|max:255',
            'package_type' => 'required|string|max:100',
            'subtotal' => 'required|numeric|min:0',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
    
        try {
            DB::transaction(function () use ($request, &$coaching) {
                $parentName = $request->parent_first_name . ' ' . $request->parent_last_name;
                $studentName = $request->student_first_name . ' ' . $request->student_last_name;
    
                // Create or update student
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
                    'student_id' => $student->id
                ]);
            });
    
            $studentName = $request->student_first_name . ' ' . $request->student_last_name;
            $parentName = $request->parent_first_name . ' ' . $request->parent_last_name;
    
            // Send email to parent
            Mail::to($request->parent_email)->send(
                new ExecutiveCoachingConfirmation(
                    $studentName,
                    $request->school,
                    $request->package_type,
                    $request->subtotal,
                    $parentName,
                    'parent'
                )
            );
    
            // Send email to student
            Mail::to($request->student_email)->send(
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
            'subtotal' => 'sometimes|numeric|min:0'
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
