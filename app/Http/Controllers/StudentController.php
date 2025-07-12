<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\SAT_ACT_Course;
use App\Models\PraticeTest;
use App\Models\CollegeAdmission;
use App\Models\CollegeEssays;
use App\Models\ExecutiveCoaching;
use Illuminate\Support\Facades\Log;

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
                    'created_at' // Removed 'total' to match column count
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
                    DB::raw("'' as school"), // Ensure 'school' is handled consistently
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
        // Manually validate to catch and debug failures
        $validator = Validator::make($request->all(), [
            'parent_name'     => 'required|string|max:255',
            'parent_phone'    => 'required|string|max:15',
            'parent_email'    => 'required|email|unique:students,parent_email',
            'student_name'    => 'required|string|max:255',
            'student_email'   => 'nullable|email|unique:students,student_email',
            'school'          => 'nullable|string|max:255',
            'bank_name'       => 'nullable|string|max:255',
            'account_number'  => 'nullable|string|max:20',
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            // Show validation errors instead of redirecting
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }
    
        // If validation passes, get validated data
        $validated = $validator->validated();
    
        // Debug output
        // dd(777, $validated);
    
        try {
            $student = Student::create($validated);
    
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
        $student = Student::findOrFail($id);

        return response()->json([
            'success' => true,
            // 'message' => 'Student details fetched successfully',
            'data' => $student
        ], 200);
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
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found.',
            ], 404);
        }

        $student->update([
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
            'message' => 'Student profile updated successfully.',
            'student' => $student,
        ]);
    }

    public function destroy($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found.',
            ], 404);
        }

        $student->delete();

        return response()->json([
            'success' => true,
            'message' => 'Student deleted successfully.',
        ]);
    }


    // GET EXAMS BY STUDENT ID

    public function getExamsByStudentId(Request $request, $studentId)
    {
        // Validate student ID
        $student = Student::find($studentId);
        if (!$student) {
            return response()->json([
                'status' => false,
                'message' => 'Student not found'
            ], 404);
        }

        try {
            $exams = [];
            DB::enableQueryLog();

            // SAT/ACT Courses
            $satActCourses = SAT_ACT_Course::where('student_id', $studentId)
                ->select(
                    DB::raw("'SAT/ACT Course' as exam_type"),
                    'package_name as package',
                    'amount as total',
                    'payment_status as status',
                    'created_at',
                    DB::raw('NULL as exam_date')
                )
                ->get()
                ->toArray();
            Log::info('SAT/ACT Courses retrieved', [
                'count' => count($satActCourses),
                'query' => DB::getQueryLog()
            ]);
            $exams = array_merge($exams, $satActCourses);
            DB::flushQueryLog();

            // Practice Tests
            $practiceTests = PraticeTest::where('student_id', $studentId)
                ->with('packages')
                ->get()
                ->map(function ($test) {
                    $packageNames = $test->packages->pluck('name')->implode(', ');
                    return [
                        'exam_type' => 'Practice Test',
                        'package' => $packageNames ?: $test->test_type,
                        'total' => $test->subtotal,
                        'status' => $test->status ?? 'booked',
                        'created_at' => $test->created_at,
                        'exam_date' => $test->date
                    ];
                })
                ->toArray();
            Log::info('Practice Tests retrieved', [
                'count' => count($practiceTests),
                'query' => DB::getQueryLog()
            ]);
            $exams = array_merge($exams, $practiceTests);
            DB::flushQueryLog();

            // College Admissions
            $collegeAdmissions = CollegeAdmission::where('student_id', $studentId)
                ->select(
                    DB::raw("'College Admission' as exam_type"),
                    'packages as package',
                    'subtotal as total',
                    'status',
                    'created_at',
                    DB::raw('NULL as exam_date')
                )
                ->get()
                ->toArray();
            Log::info('College Admissions retrieved', [
                'count' => count($collegeAdmissions),
                'query' => DB::getQueryLog()
            ]);
            $exams = array_merge($exams, $collegeAdmissions);
            DB::flushQueryLog();

            // College Essays
            $collegeEssays = CollegeEssays::where('student_id', $studentId)
                ->select(
                    DB::raw("'College Essay' as exam_type"),
                    'packages as package',
                    DB::raw('NULL as total'),
                    'status',
                    'created_at',
                    DB::raw('NULL as exam_date')
                )
                ->get()
                ->toArray();
            Log::info('College Essays retrieved', [
                'count' => count($collegeEssays),
                'query' => DB::getQueryLog()
            ]);
            $exams = array_merge($exams, $collegeEssays);
            DB::flushQueryLog();

            // Executive Coaching
            $executiveCoaching = ExecutiveCoaching::where('student_id', $studentId)
                ->select(
                    DB::raw("'Executive Coaching' as exam_type"),
                    'package_type as package',
                    'subtotal as total',
                    'status',
                    'created_at',
                    DB::raw('NULL as exam_date')
                )
                ->get()
                ->toArray();
            Log::info('Executive Coaching retrieved', [
                'count' => count($executiveCoaching),
                'query' => DB::getQueryLog()
            ]);
            $exams = array_merge($exams, $executiveCoaching);
            DB::flushQueryLog();

            // Sort all exams by created_at (newest first)
            usort($exams, function ($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });

            Log::info('Exams retrieved for student', [
                'student_id' => $studentId,
                'exam_count' => count($exams)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Exams retrieved successfully',
                'data' => [
                    'student' => [
                        'id' => $student->id,
                        'name' => $student->student_name,
                        'email' => $student->student_email
                    ],
                    'exams' => $exams
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Failed to retrieve exams for student', [
                'student_id' => $studentId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve exams: ' . $e->getMessage()
            ], 500);
        }
    }


}