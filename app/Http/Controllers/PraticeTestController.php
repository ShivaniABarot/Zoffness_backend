<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PraticeTest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Package;

class PraticeTestController extends Controller
{
    public function index()
    {
        $praticetest = PraticeTest::all();
        return view('inquiry.pratice_test', compact('praticetest'));
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
        'school' => 'nullable|string|max:255',
        'test_type' => 'required|array',
        'test_type.*' => 'exists:packages,id',
        'date' => 'required|string', // Accepting full session string
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // Calculate subtotal
    $subtotal = Package::whereIn('id', $request->test_type)->sum('price');

    // Create test entry
    $test = PraticeTest::create([
        'parent_first_name' => $request->parent_first_name,
        'parent_last_name' => $request->parent_last_name,
        'parent_phone' => $request->parent_phone,
        'parent_email' => $request->parent_email,
        'student_first_name' => $request->student_first_name,
        'student_last_name' => $request->student_last_name,
        'student_email' => $request->student_email,
        'school' => $request->school,
        'test_type' => implode(', ', $request->test_type), // If you want to store IDs as string
        'date' => $request->date,
        'subtotal' => $subtotal,
    ]);

    // Attach packages
    $test->packages()->attach($request->test_type);

    return response()->json([
        'message' => 'Practice test created successfully.',
        'data' => $test->load('packages')
    ], 201);
}


    // Show a specific practice test
    public function show($id)
    {
        $test = PraticeTest::find($id);

        if (!$test) {
            return response()->json(['message' => 'Practice test not found'], 404);
        }

        return response()->json(['data' => $test], 200);
    }

    // Update a specific practice test
    public function update(Request $request, $id)
    {
        $test = PraticeTest::find($id);

        if (!$test) {
            return response()->json(['message' => 'Practice test not found'], 404);
        }

        $test->update($request->all());
        return response()->json(['message' => 'Practice test updated', 'data' => $test], 200);
    }

    // Delete a specific practice test
    public function destroy($id)
    {
        $test = PraticeTest::find($id);

        if (!$test) {
            return response()->json(['message' => 'Practice test not found'], 404);
        }

        $test->delete();
        return response()->json(['message' => 'Practice test deleted'], 200);
    }

}
