<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PraticeTest;
use App\Models\Package;
use App\Models\Session;
use Illuminate\Support\Facades\Validator;
use App\Mail\PracticeTestBooked;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PraticeTestController extends Controller
{
    public function index()
    {
        $praticetests = PraticeTest::with('packages')->get();
        return view('inquiry.pratice_test', compact('praticetests'));
    }

    // In app/Http/Controllers/YourController.php
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parent_first_name' => 'nullable|string',
            'parent_last_name' => 'nullable|string',
            'parent_phone' => 'nullable|string|max:20',
            'parent_email' => 'nullable|email',
            'student_first_name' => 'required|string',
            'student_last_name' => 'required|string',
            'student_email' => 'required|email',
            'school' => 'nullable|string',
            'test_type' => 'required|exists:sessions,id',
            'dates' => 'required|array',
            'dates.*' => 'date_format:Y-m-d H:i',
            'bank_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'payment_status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // $session = DB::table('sessions')->where('id', 
        // $request->test_type)->first(['price_per_slot', 'title']);
        // if (!$session) {
        //     Log::error('Session not found', ['test_type' => $request->test_type]);
        //     return response()->json(['message' => 'Invalid test type'], 400);
        // }

        // $subtotal = $session->price_per_slot;
        // $testTypeName = $session->title;

        // Log::info('Subtotal and test type name fetched', [
        //     'subtotal' => $subtotal,
        //     'test_type' => $request->test_type,
        //     'test_type_name' => $testTypeName
        // ]);
        $session = DB::table('sessions')->where('id', $request->test_type)
    ->first(['price_per_slot', 'title']);

if (!$session) {
    Log::error('Session not found', ['test_type' => $request->test_type]);
    return response()->json(['message' => 'Invalid test type'], 400);
}

$datesCount = count($request->dates);
$subtotal = $session->price_per_slot * $datesCount;
$testTypeName = $session->title;

Log::info('Subtotal and test type name fetched', [
    'price_per_slot' => $session->price_per_slot,
    'dates_count' => $datesCount,
    'subtotal' => $subtotal,
    'test_type' => $request->test_type,
    'test_type_name' => $testTypeName
]);


        $parentName = trim($request->parent_first_name . ' ' . $request->parent_last_name);
        $studentName = trim($request->student_first_name . ' ' . $request->student_last_name);
        $parentDetails = [
            'name' => $parentName,
            'phone' => $request->parent_phone,
            'email' => $request->parent_email,
        ];

        try {
            $test = DB::transaction(function () use ($request, $subtotal, $parentName, $studentName) {
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

                Log::info('Student created', ['email' => $request->student_email, 'id' => $student->id]);

                $dates = $request->dates;

                Log::info('Creating practice test with data', ['test_type' => $request->test_type]);

                $test = PracticeTest::create([ // Updated to PracticeTest
                    'parent_first_name' => $request->parent_first_name,
                    'parent_last_name' => $request->parent_last_name,
                    'parent_phone' => $request->parent_phone,
                    'parent_email' => $request->parent_email,
                    'student_first_name' => $request->student_first_name,
                    'student_last_name' => $request->student_last_name,
                    'student_email' => $request->student_email,
                    'school' => $request->school,
                    'date' => json_encode(array_values($dates), JSON_UNESCAPED_UNICODE),
                    'subtotal' => $subtotal,
                    'student_id' => $student->id,
                    'test_type' => $request->test_type,
                ]);

                Log::info('Practice test created', ['id' => $test->id, 'test_type' => $test->test_type]);

                return $test;
            });

            $emailDates = json_decode($test->date, true);
            if ($emailDates === null) {
                throw new \Exception('Failed to decode date JSON: ' . json_last_error_msg());
            }

            if (!empty($request->parent_email)) {
                Mail::to($request->parent_email)->queue(
                    new PracticeTestBooked(
                        $studentName,
                        $testTypeName,
                        $emailDates,
                        $subtotal,
                        $parentName,
                        'parent',
                        $request->school,
                        $parentDetails,
                        null,
                        $request->payment_status,
                        now()->format('m-d-Y'),
                        null,
                        $request->student_email
                    )
                );
            }

            if (!empty($request->student_email)) {
                Mail::to($request->student_email)->queue(
                    new PracticeTestBooked(
                        $studentName,
                        $testTypeName,
                        $emailDates,
                        $subtotal,
                        $studentName,
                        'student',
                        $request->school,
                        $parentDetails,
                        null,
                        $request->payment_status,
                        now()->format('m-d-Y'),
                        null,
                        $request->student_email
                    )
                );
            }

             // Queue email to internal admins
             $adminEmails = ['ben.hartman@zoffnesscollegeprep.com', 'info@zoffnesscollegeprep.com'];
             $bccEmails = ['dev@bugletech.com', 'ravi.kamdar@bugletech.com'];

            // $adminEmails = ['dev@bugletech.com'];
            // $bccEmails = ['dev@bugletech.com'];
            Mail::to($adminEmails)->bcc($bccEmails)->send(
                (new PracticeTestBooked(
                    $studentName,
                    $testTypeName,
                    $emailDates,
                    $subtotal,
                    $parentDetails['name'],
                    'admin',
                    $request->school,
                    $parentDetails,
                    null,
                    $request->payment_status,
                    now()->format('m-d-Y'),
                    null,
                    $request->student_email
                ))->from('web@notifications.zoffnesscollegeprep.com', $parentDetails['name'])
                    ->replyTo($parentDetails['email'], $parentDetails['name'])
            );

            return response()->json([
                'message' => 'Practice test and student data created successfully.',
                'data' => $test
            ], 201);

        } catch (\Exception $e) {
            Log::error('Failed to create practice test or student', [
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