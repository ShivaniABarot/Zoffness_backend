<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CollegeAdmission;
use App\Models\Student;
use Illuminate\Support\Facades\Mail;
use App\Mail\CollegeAdmissionConfirmation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class CollegeAdmissionController extends Controller
{
    public function index()
    {
        $collegeadmission = CollegeAdmission::all();
        return view('inquiry.college_admission', compact('collegeadmission'));
    }

    public function college_admission(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'parent_first_name' => 'nullable|string',
            'parent_last_name' => 'nullable|string',
            'parent_phone' => 'nullable|string',
            'parent_email' => 'nullable|email',
            'student_first_name' => 'required|string',
            'student_last_name' => 'required|string',
            'student_email' => 'required',
            'packages' => 'nullable|string',
            'school' => 'nullable|string',
            'subtotal' => 'nullable|numeric|min:0',
            'initial_intake' => 'nullable|numeric|min:0',
            'five_session_package' => 'nullable|numeric|min:0',
            'ten_session_package' => 'nullable|numeric|min:0',
            'fifteen_session_package' => 'nullable|numeric|min:0',
            'twenty_session_package' => 'nullable|numeric|min:0',
            'bank_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'exam_date' => 'required|date',
            'stripe_id' => 'nullable|string',
            'payment_status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();

        // Prepare names and parent details
        $parentName = trim(($validatedData['parent_first_name'] ?? '') . ' ' . ($validatedData['parent_last_name'] ?? ''));
        $studentName = trim($validatedData['student_first_name'] . ' ' . $validatedData['student_last_name']);
        $subtotal = isset($validatedData['subtotal']) ? (float) $validatedData['subtotal'] : 0.00;

        // Prepare parent details for email
        $parentDetails = [
            'name' => $parentName,
            'phone' => $validatedData['parent_phone'] ?? null,
            'email' => $validatedData['parent_email'] ?? null,
        ];

        // Fetch Stripe details if stripe_id is provided
        Stripe::setApiKey(env('VITE_STRIPE_SECRET_KEY'));
        $stripeDetails = [
            'payment_method_type' => 'N/A',
            'last4' => 'N/A',
            'status' => 'N/A',
        ];
        if (!empty($validatedData['stripe_id'])) {
            try {
                $paymentIntent = PaymentIntent::retrieve($validatedData['stripe_id']);
                $stripeDetails = [
                    'payment_method_type' => $paymentIntent->payment_method_types[0] ?? 'N/A',
                    'last4' => $paymentIntent->charges->data[0]->payment_method_details->card->last4 ?? 'N/A',
                    'status' => $paymentIntent->status ?? 'N/A',
                ];
            } catch (\Exception $e) {
                \Log::error('Failed to fetch Stripe details: ' . $e->getMessage());
            }
        }

        try {
            $admission = DB::transaction(function () use ($validatedData, $parentName, $studentName) {
                // Create student
                $student = Student::create([
                    'student_email' => $validatedData['student_email'],
                    'parent_name' => $parentName ?: null,
                    'parent_phone' => $validatedData['parent_phone'] ?? null,
                    'parent_email' => $validatedData['parent_email'] ?? null,
                    'student_name' => $studentName,
                    'school' => $validatedData['school'] ?? null,
                    'bank_name' => $validatedData['bank_name'] ?? null,
                    'account_number' => $validatedData['account_number'] ?? null,
                    'exam_date' => $validatedData['exam_date'],
                ]);

                \Log::info('Student created', [
                    'email' => $validatedData['student_email'],
                    'id' => $student->id
                ]);

                // Create college admission record
                $admission = CollegeAdmission::create([
                    'parent_first_name' => $validatedData['parent_first_name'] ?? null,
                    'parent_last_name' => $validatedData['parent_last_name'] ?? null,
                    'parent_phone' => $validatedData['parent_phone'] ?? null,
                    'parent_email' => $validatedData['parent_email'] ?? null,
                    'student_first_name' => $validatedData['student_first_name'],
                    'student_last_name' => $validatedData['student_last_name'],
                    'student_email' => $validatedData['student_email'],
                    'packages' => $validatedData['packages'] ?? null,
                    'school' => $validatedData['school'] ?? null,
                    'subtotal' => $validatedData['subtotal'] ?? null,
                    'initial_intake' => $validatedData['initial_intake'] ?? null,
                    'five_session_package' => $validatedData['five_session_package'] ?? null,
                    'ten_session_package' => $validatedData['ten_session_package'] ?? null,
                    'fifteen_session_package' => $validatedData['fifteen_session_package'] ?? null,
                    'twenty_session_package' => $validatedData['twenty_session_package'] ?? null,
                    'bank_name' => $validatedData['bank_name'] ?? null,
                    'account_number' => $validatedData['account_number'] ?? null,
                    'exam_date' => $validatedData['exam_date'],
                    'student_id' => $student->id,
                    'stripe_id' => $validatedData['stripe_id'] ?? null,
                ]);

                return $admission;
            });

            // Send email to parent
            if (!empty($validatedData['parent_email'])) {
                Mail::to($validatedData['parent_email'])->queue(
                    new CollegeAdmissionConfirmation(
                        $studentName,
                        $validatedData['school'] ?? null,
                        $subtotal,
                        $parentName,
                        'parent',
                        $validatedData['packages'] ?? null,
                        $validatedData['exam_date'],
                        $parentDetails,
                        $validatedData['stripe_id'] ?? null,
                        $validatedData['payment_status'],
                        now()->format('m-d-Y'),
                        $stripeDetails
                    )
                );
            }

            // Send email to student
            if (!empty($validatedData['student_email'])) {
                Mail::to($validatedData['student_email'])->queue(
                    new CollegeAdmissionConfirmation(
                        $studentName,
                        $validatedData['school'] ?? null,
                        $subtotal,
                        $studentName,
                        'student',
                        $validatedData['packages'] ?? null,
                        $validatedData['exam_date'],
                        $parentDetails,
                        $validatedData['stripe_id'] ?? null,
                        $validatedData['payment_status'],
                        now()->format('m-d-Y'),
                        $stripeDetails
                    )
                );
            }

            // Send email to internal admins
            $adminEmails = ['ben.hartman@zoffnesscollegeprep.com', 'info@zoffnesscollegeprep.com', 'dev@bugletech.com'];
            $bccEmails = ['dev@bugletech.com', 'ravi.kamdar@bugletech.com'];

            Mail::to($adminEmails)
            ->bcc($bccEmails)
            ->send(
                (new CollegeAdmissionConfirmation(
                    $studentName,
                    $validatedData['school'] ?? null,
                    $subtotal,
                    $parentDetails['name'], 
                    'admin',
                    $validatedData['packages'] ?? null,
                    $validatedData['exam_date'],
                    $parentDetails,
                    $validatedData['stripe_id'] ?? null,
                    $validatedData['payment_status'],
                    now()->format('m-d-Y'),
                    $stripeDetails
                ))
                ->from('web@notifications.zoffnesscollegeprep.com', $parentDetails['name']) 
                ->replyTo($parentDetails['email'], $parentDetails['name']) 
            );
        
            
            return response()->json([
                'success' => true,
                'message' => 'College admission record created successfully.',
                'data' => $admission
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Failed to create college admission or student', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to save record: ' . $e->getMessage()
            ], 500);
        }
    }
}