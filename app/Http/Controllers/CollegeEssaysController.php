<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CollegeEssays;
use App\Models\CollageEssaysPackage;
use App\Models\Student;
use Illuminate\Support\Facades\Mail;
use App\Mail\CollegeEssayConfirmation;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class CollegeEssaysController extends Controller
{
    public function index()
    {
        $collegessays = CollegeEssays::all();
        return view('inquiry.college_essays', compact('collegessays'));
    }


    public function college_essays(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parent_first_name' => 'nullable|string',
            'parent_last_name' => 'nullable|string',
            'parent_phone' => 'nullable|string',
            'parent_email' => 'nullable|email',
            'student_first_name' => 'nullable|string',
            'student_last_name' => 'nullable|string',
            'student_email' => 'nullable|email',
            'sessions' => 'nullable|numeric',
            'packages' => 'nullable|string',
            'school' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'exam_date' => 'nullable|date',
            'stripe_id' => 'nullable|string',
            'payment_status' => 'nullable|string',
            'subtotal' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();

        /**
         * ✅ 1. Clean emails (remove prefix before underscore if present)
         */
        foreach (['parent_email', 'student_email'] as $field) {
            if (!empty($validatedData[$field]) && str_contains($validatedData[$field], '_')) {
                $parts = explode('_', $validatedData[$field], 2);
                $validatedData[$field] = $parts[1] ?? $validatedData[$field];
            }
        }

        // Prepare names and parent details
        $parentName = trim(($validatedData['parent_first_name'] ?? '') . ' ' . ($validatedData['parent_last_name'] ?? ''));
        $studentName = trim(($validatedData['student_first_name'] ?? '') . ' ' . ($validatedData['student_last_name'] ?? ''));
        $subtotal = isset($validatedData['subtotal']) ? (float) $validatedData['subtotal'] : 0.00;

        // Parent details for email
        $parentDetails = [
            'name' => $parentName,
            'phone' => $validatedData['parent_phone'] ?? null,
            'email' => $validatedData['parent_email'] ?? null,
        ];


        $packageName = null;
        if (!empty($validatedData['packages'])) {
            $package = CollageEssaysPackage::find($validatedData['packages']);
            $packageName = $package ? $package->name : $validatedData['packages'];
        }

        Stripe::setApiKey(env('VITE_STRIPE_SECRET_KEY'));
        $stripeDetails = null;

        if (!empty($validatedData['stripe_id'] ?? null)) {
            try {
                $paymentIntent = PaymentIntent::retrieve($validatedData['stripe_id']);
                $stripeDetails = [
                    'payment_method_type' => $paymentIntent->payment_method_types[0] ?? 'N/A',
                    'last4' => $paymentIntent->charges->data[0]->payment_method_details->card->last4 ?? 'N/A',
                    'status' => $paymentIntent->status ?? 'N/A',
                ];
            } catch (\Exception $e) {
                \Log::error('Failed to fetch Stripe details: ' . $e->getMessage());
                $stripeDetails = [
                    'payment_method_type' => 'N/A',
                    'last4' => 'N/A',
                    'status' => 'N/A',
                ];
            }
        } else {
            $stripeDetails = [
                'payment_method_type' => 'N/A',
                'last4' => 'N/A',
                'status' => 'N/A',
            ];
        }

        try {
            $essay = DB::transaction(function () use ($validatedData, $parentName, $studentName) {
                // Create student
                $student = Student::create([
                    'student_email' => $validatedData['student_email'] ?? null,
                    'parent_name' => $parentName,
                    'parent_phone' => $validatedData['parent_phone'] ?? null,
                    'parent_email' => $validatedData['parent_email'] ?? null,
                    'student_name' => $studentName,
                    'school' => $validatedData['school'] ?? null,
                    'bank_name' => $validatedData['bank_name'] ?? null,
                    'account_number' => $validatedData['account_number'] ?? null,
                    'exam_date' => $validatedData['exam_date'] ?? null,
                ]);

                \Log::info('Student created', [
                    'email' => $validatedData['student_email'],
                    'id' => $student->id
                ]);

                // Create essay record
                $essay = CollegeEssays::create([
                    'parent_first_name' => $validatedData['parent_first_name'] ?? null,
                    'parent_last_name' => $validatedData['parent_last_name'] ?? null,
                    'parent_phone' => $validatedData['parent_phone'] ?? null,
                    'parent_email' => $validatedData['parent_email'] ?? null,
                    'student_first_name' => $validatedData['student_first_name'] ?? null,
                    'student_last_name' => $validatedData['student_last_name'] ?? null,
                    'student_email' => $validatedData['student_email'] ?? null,
                    'sessions' => $validatedData['sessions'] ?? null,
                    'packages' => $validatedData['packages'] ?? null, // still save id/code
                    'school' => $validatedData['school'] ?? null,
                    'bank_name' => $validatedData['bank_name'] ?? null,
                    'account_number' => $validatedData['account_number'] ?? null,
                    'exam_date' => $validatedData['exam_date'] ?? null,
                    'student_id' => $student->id,
                    'stripe_id' => $validatedData['stripe_id'] ?? null,
                    'subtotal' => $validatedData['subtotal'] ?? null,
                ]);

                return $essay;
            });

            // Queue emails to parent
            if (!empty($validatedData['parent_email'])) {
                Mail::to($validatedData['parent_email'])->queue(
                    new CollegeEssayConfirmation(
                        $studentName,
                        $validatedData['school'] ?? null,
                        $subtotal,
                        $parentName,
                        'parent',
                        $packageName, // ✅ fixed
                        $validatedData['exam_date'] ?? null,
                        $parentDetails,
                        $validatedData['stripe_id'] ?? null,
                        $validatedData['payment_status'] ?? null,
                        now()->format('m-d-Y'),
                        $stripeDetails,
                        $validatedData['sessions'] ?? null,
                        $validatedData['student_email']
                        
                    )
                );
            }

            // Queue emails to student
            if (!empty($validatedData['student_email'])) {
                Mail::to($validatedData['student_email'])->queue(
                    new CollegeEssayConfirmation(
                        $studentName,
                        $validatedData['school'] ?? null,
                        $subtotal,
                        $studentName,
                        'student',
                        $packageName, // ✅ fixed
                        $validatedData['exam_date'] ?? null,
                        $parentDetails,
                        $validatedData['stripe_id'] ?? null,
                        $validatedData['payment_status'] ?? null,
                        now()->format('m-d-Y'),
                        $stripeDetails,
                        $validatedData['sessions'] ?? null,
                        $validatedData['student_email']
                    )
                );
            }

            // Queue email to internal admins
            $adminEmails = ['ben.hartman@zoffnesscollegeprep.com', 'info@zoffnesscollegeprep.com'];
            $bccEmails = ['dev@bugletech.com', 'ravi.kamdar@bugletech.com'];

            Mail::to($adminEmails)
                ->bcc($bccEmails)
                ->send(
                    (new CollegeEssayConfirmation(
                        $studentName,
                        $validatedData['school'] ?? null,
                        $subtotal,
                        $parentDetails['name'],
                        'admin',
                        $packageName, // ✅ fixed
                        $validatedData['exam_date'] ?? null,
                        $parentDetails,
                        $validatedData['stripe_id'] ?? null,
                        $validatedData['payment_status'] ?? null,
                        now()->format('m-d-Y'),
                        $stripeDetails,
                        $validatedData['sessions'] ?? null,
                        $validatedData['student_email']
                    ))
                        ->from('web@notifications.zoffnesscollegeprep.com', $parentDetails['name'])
                        ->replyTo($parentDetails['email'], $parentDetails['name'])
                );

            return response()->json([
                'status' => true,
                'message' => 'College essay form submitted successfully.',
                'data' => $essay
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Failed to create college essay or student', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to save form: ' . $e->getMessage()
            ], 500);
        }
    }


    // public function college_essays(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'parent_first_name' => 'nullable|string',
    //         'parent_last_name' => 'nullable|string',
    //         'parent_phone' => 'nullable|string',
    //         'parent_email' => 'nullable',
    //         'student_first_name' => 'nullable|string',
    //         'student_last_name' => 'nullable|string',
    //         'student_email' => 'nullable',
    //         'sessions' => 'nullable|numeric',
    //         'packages' => 'nullable|string',
    //         'school' => 'nullable|string',
    //         'bank_name' => 'nullable|string',
    //         'account_number' => 'nullable|string',
    //         'exam_date' => 'nullable|date',
    //         'stripe_id' => 'nullable|string',
    //         'payment_status' => 'nullable|string',
    //         'subtotal' => 'nullable|numeric',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }

    //     $validatedData = $validator->validated();

    //     // Prepare names and parent details
    //     $parentName = trim(($validatedData['parent_first_name'] ?? '') . ' ' . ($validatedData['parent_last_name'] ?? ''));
    //     $studentName = trim(($validatedData['student_first_name'] ?? '') . ' ' . ($validatedData['student_last_name'] ?? ''));
    //     $subtotal = isset($validatedData['subtotal']) ? (float) $validatedData['subtotal'] : 0.00;

    //     // Prepare parent details for email
    //     $parentDetails = [
    //         'name' => $parentName,
    //         'phone' => $validatedData['parent_phone'] ?? null,
    //         'email' => $validatedData['parent_email'] ?? null,
    //     ];

    //     Stripe::setApiKey(env('VITE_STRIPE_SECRET_KEY'));
    //     $stripeDetails = null;

    //     if (!empty($validatedData['stripe_id'] ?? null)) {
    //         try {
    //             $paymentIntent = PaymentIntent::retrieve($validatedData['stripe_id']);
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
    //         $essay = DB::transaction(function () use ($validatedData, $parentName, $studentName) {
    //             // Create student
    //             $student = Student::create([
    //                 'student_email' => $validatedData['student_email'] ?? null,
    //                 'parent_name' => $parentName,
    //                 'parent_phone' => $validatedData['parent_phone'] ?? null,
    //                 'parent_email' => $validatedData['parent_email'] ?? null,
    //                 'student_name' => $studentName,
    //                 'school' => $validatedData['school'] ?? null,
    //                 'bank_name' => $validatedData['bank_name'] ?? null,
    //                 'account_number' => $validatedData['account_number'] ?? null,
    //                 'exam_date' => $validatedData['exam_date'] ?? null,
    //             ]);

    //             \Log::info('Student created', [
    //                 'email' => $validatedData['student_email'],
    //                 'id' => $student->id
    //             ]);

    //             // Create essay record
    //             $essay = CollegeEssays::create([
    //                 'parent_first_name'  => $validatedData['parent_first_name'] ?? null,
    //                 'parent_last_name'   => $validatedData['parent_last_name'] ?? null,
    //                 'parent_phone'       => $validatedData['parent_phone'] ?? null,
    //                 'parent_email'       => $validatedData['parent_email'] ?? null,
    //                 'student_first_name' => $validatedData['student_first_name'] ?? null,
    //                 'student_last_name'  => $validatedData['student_last_name'] ?? null,
    //                 'student_email'      => $validatedData['student_email'] ?? null,
    //                 'sessions'           => $validatedData['sessions'] ?? null,
    //                 'packages'           => $validatedData['packages'] ?? null,
    //                 'school'             => $validatedData['school'] ?? null,
    //                 'bank_name'          => $validatedData['bank_name'] ?? null,
    //                 'account_number'     => $validatedData['account_number'] ?? null,
    //                 'exam_date'          => $validatedData['exam_date'] ?? null,
    //                 'student_id'         => $student->id,
    //                 'stripe_id'          => $validatedData['stripe_id'] ?? null,
    //                 'subtotal'           => $validatedData['subtotal'] ?? null,
    //             ]);

    //             return $essay;
    //         });

    //         // Queue emails to parent
    //         if (!empty($validatedData['parent_email'])) {
    //             Mail::to($validatedData['parent_email'])->queue(
    //                 new CollegeEssayConfirmation(
    //                     $studentName,
    //                     $validatedData['school'] ?? null, // Fix: Provide fallback
    //                     $subtotal,
    //                     $parentName,
    //                     'parent',
    //                     $validatedData['packages'] ?? null,
    //                     $validatedData['exam_date'] ?? null,
    //                     $parentDetails,
    //                     $validatedData['stripe_id'] ?? null,
    //                     $validatedData['payment_status'] ?? null,
    //                     now()->format('m-d-Y'),
    //                     $stripeDetails,
    //                     $validatedData['sessions'] ?? null
    //                 )
    //             );
    //         }

    //         // Queue emails to student
    //         if (!empty($validatedData['student_email'])) {
    //             Mail::to($validatedData['student_email'])->queue(
    //                 new CollegeEssayConfirmation(
    //                     $studentName,
    //                     $validatedData['school'] ?? null, // Fix: Provide fallback
    //                     $subtotal,
    //                     $studentName,
    //                     'student',
    //                     $validatedData['packages'] ?? null,
    //                     $validatedData['exam_date'] ?? null,
    //                     $parentDetails,
    //                     $validatedData['stripe_id'] ?? null,
    //                     $validatedData['payment_status'] ?? null,
    //                     now()->format('m-d-Y'),
    //                     $stripeDetails,
    //                     $validatedData['sessions'] ?? null
    //                 )
    //             );
    //         }

    //         // Queue email to internal admins
    //         $adminEmails = ['ben.hartman@zoffnesscollegeprep.com', 'info@zoffnesscollegeprep.com', 'dev@bugletech.com'];
    //         $bccEmails = ['dev@bugletech.com', 'ravi.kamdar@bugletech.com'];

    //         Mail::to($adminEmails)
    //             ->bcc($bccEmails)
    //             ->send(
    //                 (new CollegeEssayConfirmation(
    //                     $studentName,
    //                     $validatedData['school'] ?? null, // Fix: Provide fallback
    //                     $subtotal,
    //                     $parentDetails['name'],
    //                     'admin',
    //                     $validatedData['packages'] ?? null,
    //                     $validatedData['exam_date'] ?? null,
    //                     $parentDetails,
    //                     $validatedData['stripe_id'] ?? null,
    //                     $validatedData['payment_status'] ?? null,
    //                     now()->format('m-d-Y'),
    //                     $stripeDetails,
    //                     $validatedData['sessions'] ?? null
    //                 ))
    //                 ->from('web@notifications.zoffnesscollegeprep.com', $parentDetails['name'])
    //                 ->replyTo($parentDetails['email'], $parentDetails['name'])
    //             );

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'College essay form submitted successfully.',
    //             'data' => $essay
    //         ], 201);

    //     } catch (\Exception $e) {
    //         \Log::error('Failed to create college essay or student', [
    //             'error' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString()
    //         ]);

    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Failed to save form: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }
}