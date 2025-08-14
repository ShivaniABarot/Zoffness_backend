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
            'test_type' => 'required|exists:sessions,id',
            'date' => 'nullable|array',
            'date.*' => 'date_format:Y-m-d H:i',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'payment_status' => 'required|string|in:Success,Failed,Pending',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $subtotal = \DB::table('sessions')->where('id', $request->test_type)->value('price_per_slot');
        \Log::info('Subtotal fetched', ['subtotal' => $subtotal, 'test_type' => $request->test_type]);

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

                \Log::info('Student created', [
                    'email' => $request->student_email,
                    'id' => $student->id
                ]);

                $dates = is_array($request->date) ? $request->date : ($request->date ? [$request->date] : null);

                $test = PraticeTest::create([
                    'parent_first_name' => $request->parent_first_name,
                    'parent_last_name' => $request->parent_last_name,
                    'parent_phone' => $request->parent_phone,
                    'parent_email' => $request->parent_email,
                    'student_first_name' => $request->student_first_name,
                    'student_last_name' => $request->student_last_name,
                    'student_email' => $request->student_email,
                    'school' => $request->school,
                    'date' => $dates ? json_encode($dates) : null,
                    'subtotal' => $subtotal,
                    'student_id' => $student->id,
                    'session_id' => $request->test_type
                ]);

                return $test;
            });

            $testTypeName = DB::table('sessions')->where('id', $request->test_type)->value('title');

            if (!empty($request->parent_email)) {
                Mail::to($request->parent_email)->queue(
                    new PracticeTestBooked(
                        $studentName,
                        $testTypeName,
                        $request->date,
                        $subtotal,
                        $parentName,
                        'parent',
                        $request->school,
                        $parentDetails,
                        null, // stripe_id removed
                        $request->payment_status,
                        now()->format('m-d-Y'),
                        null // stripeDetails removed
                    )
                );
            }

            if (!empty($request->student_email)) {
                Mail::to($request->student_email)->queue(
                    new PracticeTestBooked(
                        $studentName,
                        $testTypeName,
                        $request->date,
                        $subtotal,
                        $studentName,
                        'student',
                        $request->school,
                        $parentDetails,
                        null,
                        $request->payment_status,
                        now()->format('m-d-Y'),
                        null
                    )
                );
            }

            $adminEmails = ['ben.hartman@zoffnesscollegeprep.com', 'info@zoffnesscollegeprep.com', 'dev@bugletech.com'];
            $bccEmails = ['dev@bugletech.com', 'ravi.kamdar@bugletech.com'];

            Mail::to($adminEmails)
                ->bcc($bccEmails)
                ->send(
                    (new PracticeTestBooked(
                        $studentName,
                        $testTypeName,
                        $request->date,
                        $subtotal,
                        $parentDetails['name'], // show parent's name instead of "Admin Team"
                        'admin',
                        $request->school,
                        $parentDetails,
                        null,
                        $request->payment_status,
                        now()->format('m-d-Y'),
                        null
                    ))
                        ->from('web@notifications.zoffnesscollegeprep.com', $parentDetails['name'])
                        ->replyTo($parentDetails['email'], $parentDetails['name'])
                );


            return response()->json([
                'message' => 'Practice test and student data created successfully.',
                'data' => $test
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


    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'parent_first_name' => 'nullable|string|max:255',
    //         'parent_last_name' => 'nullable|string|max:255',
    //         'parent_phone' => 'nullable|string|max:20',
    //         'parent_email' => 'nullable|email|max:255',
    //         'student_first_name' => 'required|string|max:255',
    //         'student_last_name' => 'required|string|max:255',
    //         'student_email' => 'required|email|max:255',
    //         'school' => 'nullable|string|max:255',
    //         'test_type' => 'required|exists:sessions,id', // Single select
    //         'date' => 'nullable|array', // Array for multiple dates, optional
    //         'date.*' => 'date_format:Y-m-d H:i', // Validate each date if provided as array
    //         'bank_name' => 'nullable|string|max:255',
    //         'account_number' => 'nullable|string|max:255',
    //         'stripe_id' => 'nullable|string',
    //         'payment_status' => 'required|string|in:Success,Failed,Pending',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     $subtotal = \DB::table('sessions')->where('id', $request->test_type)->value('price_per_slot');
    //     \Log::info('Subtotal fetched', ['subtotal' => $subtotal, 'test_type' => $request->test_type]); // Debug log
    //     $parentName = trim($request->parent_first_name . ' ' . $request->parent_last_name);
    //     $studentName = trim($request->student_first_name . ' ' . $request->student_last_name);

    //     // Prepare parent details for email
    //     $parentDetails = [
    //         'name' => $parentName,
    //         'phone' => $request->parent_phone,
    //         'email' => $request->parent_email,
    //     ];

    //     // Fetch Stripe details if stripe_id is provided
    //     Stripe::setApiKey(env('VITE_STRIPE_SECRET_KEY'));
    //     $stripeDetails = null;
    //     if ($request->stripe_id) {
    //         try {
    //             $paymentIntent = PaymentIntent::retrieve($request->stripe_id);
    //             $stripeDetails = [
    //                 'payment_method_type' => $paymentIntent->payment_method_types[0] ?? 'N/A',
    //                 'last4' => $paymentIntent->charges->data[0]->payment_method_details->card->last4 ?? 'N/A',
    //                 'status' => $paymentIntent->status ?? 'N/A',
    //             ];
    //         } catch (\Exception $e) {
    //             \Log::error('Failed to fetch Stripe details: ' . $e->getMessage());
    //             $stripeDetails = [
    //                 'payment_method_type' => 'N/A',
    //                 'last4' => 'N/A',
    //                 'status' => 'N/A',
    //             ];
    //         }
    //     } else {
    //         $stripeDetails = [
    //             'payment_method_type' => 'N/A',
    //             'last4' => 'N/A',
    //             'status' => 'N/A',
    //         ];
    //     }

    //     try {
    //         $test = DB::transaction(function () use ($request, $subtotal, $parentName, $studentName) {
    //             // Create student
    //             $student = Student::create([
    //                 'student_email' => $request->student_email,
    //                 'parent_name' => $parentName,
    //                 'parent_phone' => $request->parent_phone,
    //                 'parent_email' => $request->parent_email,
    //                 'student_name' => $studentName,
    //                 'school' => $request->school,
    //                 'bank_name' => $request->bank_name,
    //                 'account_number' => $request->account_number
    //             ]);

    //             \Log::info('Student created', [
    //                 'email' => $request->student_email,
    //                 'id' => $student->id
    //             ]);

    //             // Handle date as array or single value
    //             $dates = is_array($request->date) ? $request->date : ($request->date ? [$request->date] : null);

    //             // Create practice test
    //             $test = PraticeTest::create([
    //                 'parent_first_name' => $request->parent_first_name,
    //                 'parent_last_name' => $request->parent_last_name,
    //                 'parent_phone' => $request->parent_phone,
    //                 'parent_email' => $request->parent_email,
    //                 'student_first_name' => $request->student_first_name,
    //                 'student_last_name' => $request->student_last_name,
    //                 'student_email' => $request->student_email,
    //                 'school' => $request->school,
    //                 'date' => $dates ? json_encode($dates) : null, // Store as JSON if multiple dates
    //                 'subtotal' => $subtotal,
    //                 'student_id' => $student->id,
    //                 'stripe_id' => $request->stripe_id,
    //                 'session_id' => $request->test_type // Store single session ID directly
    //             ]);

    //             return $test;
    //         });

    //         // Get test type name for email
    //         $testTypeName = DB::table('sessions')->where('id', $request->test_type)->value('title');

    //         // Send email to parent
    //         if (!empty($request->parent_email)) {
    //             Mail::to($request->parent_email)->queue(
    //                 new PracticeTestBooked(
    //                     $studentName,
    //                     $testTypeName,
    //                     $request->date, // Pass as is for email rendering
    //                     $subtotal,
    //                     $parentName,
    //                     'parent',
    //                     $request->school,
    //                     $parentDetails,
    //                     $request->stripe_id,
    //                     $request->payment_status,
    //                     now()->format('m-d-Y'),
    //                     $stripeDetails
    //                 )
    //             );
    //         }

    //         // Send email to student
    //         if (!empty($request->student_email)) {
    //             Mail::to($request->student_email)->queue(
    //                 new PracticeTestBooked(
    //                     $studentName,
    //                     $testTypeName,
    //                     $request->date, // Pass as is for email rendering
    //                     $subtotal,
    //                     $studentName,
    //                     'student',
    //                     $request->school,
    //                     $parentDetails,
    //                     $request->stripe_id,
    //                     $request->payment_status,
    //                     now()->format('m-d-Y'),
    //                     $stripeDetails
    //                 )
    //             );
    //         }

    //         // Send email to internal admins
    //         $adminEmails = ['ben.hartman@zoffnesscollegeprep.com', 'info@zoffnesscollegeprep.com', 'dev@bugletech.com'];
    //         Mail::to($adminEmails)->queue(
    //             new PracticeTestBooked(
    //                 $studentName,
    //                 $testTypeName,
    //                 $request->date, // Pass as is for email rendering
    //                 $subtotal,
    //                 'Admin Team',
    //                 'admin',
    //                 $request->school,
    //                 $parentDetails,
    //                 $request->stripe_id,
    //                 $request->payment_status,
    //                 now()->format('m-d-Y'),
    //                 $stripeDetails
    //             )
    //         );

    //         return response()->json([
    //             'message' => 'Practice test and student data created successfully.',
    //             'data' => $test
    //         ], 201);

    //     } catch (\Exception $e) {
    //         \Log::error('Failed to create practice test or student', [
    //             'error' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString()
    //         ]);

    //         return response()->json([
    //             'message' => 'Failed to create practice test or student: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

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