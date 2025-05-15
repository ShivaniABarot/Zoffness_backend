<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    // Display the list of students
    public function index()
    {
        $students = Student::all(); // Fetch all students, consider pagination for large datasets
        return view('students.index', compact('students'));
    }
    // public function index()
    // {
    //     // Fetch data from both sat_act_course_reg and students tables
    //     $students = DB::table('sat_act_course_reg')
    //         ->leftJoin('students', 'sat_act_course_reg.student_email', '=', 'students.student_email')
    //         ->select(
    //             'sat_act_course_reg.id',
    //             'sat_act_course_reg.parent_firstname',
    //             'sat_act_course_reg.parent_lastname',
    //             'sat_act_course_reg.parent_email',
    //             'sat_act_course_reg.student_firstname',
    //             'sat_act_course_reg.student_lastname',
    //             'sat_act_course_reg.school',
    //             'students.student_email as student_email',
    //             'students.bank_name',
    //             'students.account_number'
    //         )
    //         ->get();
    
    //     return view('students.index', compact('students'));
    // }

    // Display the form to add a new student
    public function create()
    {
        return view('students.create');
    }

    // Store a new student in the database
    public function store(Request $request)
{
    // Validate the form inputs
    $validated = $request->validate([
        'parent_name' => 'required|string|max:255',
        'parent_phone' => 'required|string|max:15',
        'parent_email' => 'required|email|unique:students,parent_email',
        'student_name' => 'required|string|max:255',
        'student_email' => 'nullable|email|unique:students,student_email',
        'school' => 'nullable|string|max:255',
        'bank_name' => 'nullable|string|max:255',
        'account_number' => 'nullable|string|max:20',
    ]);

    try {
        // Create the Student profile
        $student = Student::create([
            'parent_name' => $request->parent_name,
            'parent_phone' => $request->parent_phone,
            'parent_email' => $request->parent_email,
            'student_name' => $request->student_name,
            'student_email' => $request->student_email,
            'school' => $request->school,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Student profile created successfully.',
            'student' => $student,
        ]);
    } catch (\Exception $e) {
        \Log::error('Student Store Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while creating the student profile.',
            'error' => $e->getMessage(),
        ], 500);
    }
}


    // Show a specific student
    public function show($id)
    {
        $student = Student::findOrFail($id); 
        return view('students.view', compact('student'));
    }
 
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('students.edit', compact('student'));
    }

    // Update an existing student
    public function update(Request $request, $id)
    {
        // Validate the updated data
        $validator = Validator::make($request->all(), [
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:15',
            'parent_email' => 'required|email|unique:students,parent_email,' . $id,
            'student_name' => 'required|string|max:255',
            'student_email' => 'nullable|email|unique:students,student_email,' . $id,
            'school' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:20',
        ]);

        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->route('students.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }
            // Find the student record by ID
            $student = Student::findOrFail($id);

            // Update the student profile with the validated data
            $student->parent_name = $request->parent_name;
            $student->parent_phone = $request->parent_phone;
            $student->parent_email = $request->parent_email;
            $student->student_name = $request->student_name;
            $student->student_email = $request->student_email;
            $student->school = $request->school;
            $student->bank_name = $request->bank_name;
            $student->account_number = $request->account_number;

            // Save the updated student data
            $student->save();

            // Return success response
            return redirect()->route('student')->with('success', 'Student profile updated successfully.');
       
    }
    // Delete a student
    // public function destroy($id)
    // {
    //     // Find the student by ID
    //     $student = Student::find($id);

    //     if ($student) {
    //         try {
    //             // Delete the student record
    //             $student->delete();

    //             // Return success response
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Student profile deleted successfully.',
    //             ]);
    //         } catch (\Exception $e) {
    //             // Return error response
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'An error occurred while deleting the student profile.',
    //                 'error' => $e->getMessage(),
    //             ], 500);
    //         }
    //     }

    //     // If the student doesn't exist
    //     return response()->json([
    //         'success' => false,
    //         'message' => 'Student not found.',
    //     ], 404);
    // }

    public function destroy($id)
    {
        $student = Student::findOrFail($id); // Retrieve student or fail
        $student->delete(); // Delete the student
        
        return redirect()->route('student')->with('success', 'Student deleted successfully.');
    }
    
}
