<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SAT_ACT_Course;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\SatActCourseConfirmation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class SATACTCourseController extends Controller
{
    public function sat_act_course()
    {
        $sat_act_course = SAT_ACT_Course::with('packages')->get();
        return view('inquiry.sat_act_course', compact('sat_act_course'));
    }

    public function new_sat_act(Request $request)
    {
        // Initialize Stripe with your secret key
        Stripe::setApiKey(env('VITE_STRIPE_SECRET_KEY'));
        // dd(env('VITE_STRIPE_SECRET_KEY'));

        $validator = Validator::make($request->all(), [
            'parent_firstname' => 'nullable|string',
            'parent_lastname' => 'nullable|string',
            'parent_phone' => 'nullable|string',
            'parent_email' => 'nullable|email',
            'student_firstname' => 'required|string',
            'student_lastname' => 'required|string',
            'student_email' => 'required|email',
            'school' => 'required|string',
            'package_name' => 'required|string',
            'payment_status' => 'required|string|in:Success,Failed,Pending',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'exam_date' => 'required|date',
            'amount' => 'required|numeric',
            'stripe_id' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $totalAmount = is_array($request->amount) ? collect($request->amount)->sum() : (float) $request->amount;
        $parentName = trim($request->parent_firstname . ' ' . $request->parent_lastname);
        $studentName = trim($request->student_firstname . ' ' . $request->student_lastname);

    //   dd('Received stripe_id: ' . $request->stripe_id);


        // Fetch parent details from users table
        $parent = null;
        if ($request->parent_email) {
            $parent = User::where('email', $request->parent_email)
                ->where('firstname', $request->parent_firstname)
                ->where('lastname', $request->parent_lastname)
                ->where('phone_no', $request->parent_phone)
                ->first();
        }

        // Prepare parent details for email
        $parentDetails = [
            'name' => $parent ? trim($parent->firstname . ' ' . $parent->lastname) : $parentName,
            'phone' => $parent ? $parent->phone_no : $request->parent_phone,
            'email' => $parent ? $parent->email : $request->parent_email,
        ];

        // Fetch Stripe details if stripe_id is provided
        $stripeDetails = null;
        if ($request->stripe_id) {
            // dd($stripeDetails);
            try {
                $paymentIntent = PaymentIntent::retrieve($request->stripe_id);
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
            $SAT_ACT_Course = DB::transaction(function () use ($request, $parentName, $studentName, $totalAmount, $parent) {
                $student = Student::create([
                    'student_email' => $request->student_email,
                    'parent_name' => $parent ? trim($parent->firstname . ' ' . $parent->lastname) : $parentName,
                    'parent_phone' => $parent ? $parent->phone_no : $request->parent_phone,
                    'parent_email' => $parent ? $parent->email : $request->parent_email,
                    'student_name' => $studentName,
                    'school' => $request->school,
                    'bank_name' => $request->bank_name,
                    'account_number' => $request->account_number,
                    'exam_date' => $request->exam_date,
                    'amount' => $totalAmount
                ]);

                return SAT_ACT_Course::create([
                    'parent_firstname' => $parent ? $parent->firstname : $request->parent_firstname,
                    'parent_lastname' => $parent ? $parent->lastname : $request->parent_lastname,
                    'parent_phone' => $parent ? $parent->phone_no : $request->parent_phone,
                    'parent_email' => $parent ? $parent->email : $request->parent_email,
                    'student_firstname' => $request->student_firstname,
                    'student_lastname' => $request->student_lastname,
                    'student_email' => $request->student_email,
                    'school' => $request->school,
                    'amount' => $totalAmount,
                    'package_name' => $request->package_name,
                    'exam_date' => $request->exam_date,
                    'student_id' => $student->id,
                    'stripe_id' => $request->stripe_id
                ]);
            });

            // Send email to parent
            if (!empty($request->parent_email)) {
                Mail::to($request->parent_email)->queue(
                    new SatActCourseConfirmation(
                        $studentName,
                        $request->school,
                        $request->package_name,
                        $totalAmount,
                        $request->payment_status,
                        $parentDetails['name'],
                        'parent',
                        $request->exam_date,
                        $request->stripe_id,
                        now()->format('m-d-Y'),
                        $stripeDetails,
                        $parentDetails
                    )
                );
            }

            // Send email to student
            if (!empty($request->student_email)) {
                Mail::to($request->student_email)->queue(
                    new SatActCourseConfirmation(
                        $studentName,
                        $request->school,
                        $request->package_name,
                        $totalAmount,
                        $request->payment_status,
                        $studentName,
                        'student',
                        $request->exam_date,
                        $request->stripe_id,
                        now()->format('m-d-Y'),
                        $stripeDetails,
                        $parentDetails
                    )
                );
            }

            // Send email to internal admins
            // $adminEmails = ['dev@bugletech.com'];
            $adminEmails = ['ben.hartman@zoffnesscollegeprep.com', 'info@zoffnesscollegeprep.com','dev@bugletech.com'];

            Mail::to($adminEmails)->queue(
                new SatActCourseConfirmation(
                    $studentName,
                    $request->school,
                    $request->package_name,
                    $totalAmount,
                    $request->payment_status,
                    'Admin Team',
                    'admin',
                    $request->exam_date,
                    $request->stripe_id,
                    now()->format('m-d-Y'),
                    $stripeDetails,
                    $parentDetails
                )
            );

            return response()->json([
                'success' => true,
                'message' => 'SAT/ACT registration and student data created successfully',
                'data' => $SAT_ACT_Course,
                'payment_status' => $request->payment_status
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create record: ' . $e->getMessage()
            ], 500);
        }
    }
}