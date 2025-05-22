<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index()
    {
        // Use a UNION query to fetch student names, parent names, parent email, and school from all tables
        $studentsFromTables = DB::table('sat_act_course_reg')
        ->select(
            'student_email',
            DB::raw("CONCAT(student_firstname, ' ', student_lastname) as student_name"),
            DB::raw("CONCAT(parent_firstname, ' ', parent_lastname) as parent_name"),
            'parent_email',
            'school',
            'created_at'
        )
            ->union(DB::table('practice_tests')
                ->select(
                    'student_email',
                    DB::raw("CONCAT(COALESCE(student_first_name, ''), ' ', COALESCE(student_last_name, '')) as student_name"),
                    DB::raw("CONCAT(COALESCE(parent_first_name, ''), ' ', COALESCE(parent_last_name, '')) as parent_name"),
                    'parent_email',
                    'school',
                    'created_at'
                ))
            ->union(DB::table('college_admissions')
                ->select(
                    'student_email',
                    DB::raw("CONCAT(COALESCE(student_first_name, ''), ' ', COALESCE(student_last_name, '')) as student_name"),
                    DB::raw("CONCAT(COALESCE(parent_first_name, ''), ' ', COALESCE(parent_last_name, '')) as parent_name"),
                    'parent_email',
                    'school',
                    'created_at'
                ))
                ->union(DB::table('college_essays')
                ->select(
                    'student_email',
                    DB::raw("CONCAT(COALESCE(student_first_name, ''), ' ', COALESCE(student_last_name, '')) as student_name"),
                    DB::raw("CONCAT(COALESCE(parent_first_name, ''), ' ', COALESCE(parent_last_name, '')) as parent_name"),
                    'parent_email',
                    DB::raw("'' as school"), // <-- Add this line if school column does not exist
                    'created_at'
                ))
            
            ->union(DB::table('enrollments')
                ->select(
                    'student_email',
                    DB::raw("CONCAT(COALESCE(student_first_name, ''), ' ', COALESCE(student_last_name, '')) as student_name"),
                    DB::raw("CONCAT(COALESCE(parent_first_name, ''), ' ', COALESCE(parent_last_name, '')) as parent_name"),
                    'parent_email',
                    'school',
                    'created_at'
                ))
            ->union(DB::table('executive_function_coaching')
                ->select(
                    'student_email',
                    DB::raw("CONCAT(COALESCE(student_first_name, ''), ' ', COALESCE(student_last_name, '')) as student_name"),
                    DB::raw("CONCAT(COALESCE(parent_first_name, ''), ' ', COALESCE(parent_last_name, '')) as parent_name"),
                    'parent_email',
                    'school',
                    'created_at'
                ));
    
        // Deduplicate by student_email, sort by created_at to resolve conflicts, and join with students table for id
        $students = DB::table(DB::raw("({$studentsFromTables->toSql()}) as combined_students"))
            ->mergeBindings($studentsFromTables)
            ->select(
                'combined_students.student_email',
                'combined_students.student_name',
                'combined_students.parent_name',
                'combined_students.parent_email',
                'combined_students.school',
                'students.id'
            )
            ->distinct('combined_students.student_email')
            ->leftJoin('students', 'combined_students.student_email', '=', 'students.student_email')
            ->orderBy('combined_students.created_at', 'desc') // Ensure most recent record is used for duplicates
            ->get();
    
        return view('students.index', compact('students'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
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

    public function show($id)
    {
        $student = Student::with([
            'collegeAdmissions' => function ($query) {
                $query->select('id', 'student_email', 'packages', 'created_at', 'status');
            },
            'collegeEssays' => function ($query) {
                $query->select('id', 'student_email', 'packages', 'created_at', 'status');
            },
            'enrollments' => function ($query) {
                $query->select('id', 'student_email', 'packages', 'created_at', 'status');
            },
            'satActCourseRegistrations' => function ($query) {
                $query->select('id', 'student_email', 'package_name', 'created_at', 'status');
            },
            'practiceTests' => function ($query) {
                $query->select('id', 'student_email', 'test_type', 'date', 'created_at', 'status');
            },
            'executiveFunctionCoaching' => function ($query) {
                $query->select('id', 'student_email', 'package_type', 'created_at', 'status');
            }
        ])->findOrFail($id);

        return view('students.view', compact('student'));
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
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

        if ($validator->fails()) {
            return redirect()->route('students.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        $student = Student::findOrFail($id);

        $student->parent_name = $request->parent_name;
        $student->parent_phone = $request->parent_phone;
        $student->parent_email = $request->parent_email;
        $student->student_name = $request->student_name;
        $student->student_email = $request->student_email;
        $student->school = $request->school;
        $student->bank_name = $request->bank_name;
        $student->account_number = $request->account_number;

        $student->save();

        return redirect()->route('students.index')->with('success', 'Student profile updated successfully.');
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }
}