<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PraticeTest;
use App\Models\Package;
use Illuminate\Support\Facades\Validator;
use App\Mail\PracticeTestBooked;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\Student;


class PraticeTestController extends Controller
{
    public function index()
    {
        $praticetests = PraticeTest::with('packages')->get();
        return view('inquiry.pratice_test', compact('praticetests'));
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
            'student_email' => 'required|email|max:255',
            'school' => 'nullable|string|max:255',
            'test_type' => 'required|array',
            'test_type.*' => 'exists:packages,id',
            'date' => 'required|date',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $subtotal = Package::whereIn('id', $request->test_type)->sum('price');
        $parentName = trim($request->parent_first_name . ' ' . $request->parent_last_name);
        $studentName = trim($request->student_first_name . ' ' . $request->student_last_name);
    
        try {
            $test = DB::transaction(function () use ($request, $subtotal, $parentName, $studentName) {
                // Create student (force create, not update)
                $student = Student::create([
                    'student_email' => $request->student_email,
                    'parent_name' => $parentName,
                    'parent_phone' => $request->parent_phone,
                    'parent_email' => $request->parent_email,
                    'student_name' => $studentName,
                    'school' => $request->school,
                    'bank_name' => $request->bank_name,
                    'account_number' => $request->account_number
                ]);
    
                \Log::info('Student created', [
                    'email' => $request->student_email,
                    'id' => $student->id
                ]);
    
                // Create practice test
                $test = PraticeTest::create([
                    'parent_first_name' => $request->parent_first_name,
                    'parent_last_name' => $request->parent_last_name,
                    'parent_phone' => $request->parent_phone,
                    'parent_email' => $request->parent_email,
                    'student_first_name' => $request->student_first_name,
                    'student_last_name' => $request->student_last_name,
                    'student_email' => $request->student_email,
                    'school' => $request->school,
                    'date' => $request->date,
                    'subtotal' => $subtotal,
                    'student_id' => $student->id
                ]);
    
                // Sync selected packages
                $test->packages()->sync($request->test_type);
    
                return $test;
            });
    
            // Get test type names for email
            $testTypeNames = Package::whereIn('id', $request->test_type)->pluck('name')->toArray();
            $testTypeList = implode(', ', $testTypeNames);
    
            // Send email to parent
            if (!empty($request->parent_email)) {
                Mail::to($request->parent_email)->queue(
                    new PracticeTestBooked(
                        $studentName,
                        $testTypeList,
                        $request->date,
                        $subtotal,
                        $parentName,
                        'parent'
                    )
                );
            }
    
            // Send email to student
            if (!empty($request->student_email)) {
                Mail::to($request->student_email)->queue(
                    new PracticeTestBooked(
                        $studentName,
                        $testTypeList,
                        $request->date,
                        $subtotal,
                        $studentName,
                        'student'
                    )
                );
            }
    
            return response()->json([
                'message' => 'Practice test and student data created successfully.',
                'data' => $test->load('packages')
            ], 201);
    
        } catch (\Exception $e) {
            \Log::error('Failed to create practice test or student', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
    
            return response()->json([
                'message' => 'Failed to create practice test or student: ' . $e->getMessage()
            ], 500);
        }
    }
    




    public function show($id)
    {
        $test = PraticeTest::with('packages')->find($id);

        if (!$test) {
            return response()->json(['message' => 'Practice test not found'], 404);
        }

        return response()->json(['data' => $test], 200);
    }

    public function update(Request $request, $id)
    {
        $test = PraticeTest::find($id);

        if (!$test) {
            return response()->json(['message' => 'Practice test not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'parent_first_name' => 'sometimes|required|string|max:255',
            'parent_last_name' => 'sometimes|required|string|max:255',
            'parent_phone' => 'sometimes|required|string|max:20',
            'parent_email' => 'sometimes|required|email|max:255',
            'student_first_name' => 'sometimes|required|string|max:255',
            'student_last_name' => 'sometimes|required|string|max:255',
            'student_email' => 'sometimes|required|email|max:255',
            'school' => 'nullable|string|max:255',
            'test_type' => 'sometimes|required|array',
            'test_type.*' => 'exists:packages,id',
            'date' => 'sometimes|required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->has('test_type')) {
            $subtotal = Package::whereIn('id', $request->test_type)->sum('price');
            $test->update([
                'test_type' => implode(', ', $request->test_type),
                'subtotal' => $subtotal,
            ]);
            $test->packages()->sync($request->test_type);
        }

        $test->update($request->except(['test_type']));

        return response()->json(['message' => 'Practice test updated', 'data' => $test->load('packages')], 200);
    }

    public function destroy($id)
    {
        $test = PraticeTest::find($id);

        if (!$test) {
            return response()->json(['message' => 'Practice test not found'], 404);
        }

        $test->packages()->detach();
        $test->delete();

        return response()->json(['message' => 'Practice test deleted'], 200);
    }
}
