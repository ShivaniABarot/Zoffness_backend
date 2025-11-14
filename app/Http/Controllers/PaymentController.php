<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Package;
use App\Models\Session;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use App\Mail\PaymentConfirmationMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Stripe\Stripe;
use App\Models\User;
use App\Mail\PaymentConfirmation;
use Illuminate\Support\Facades\Auth;
// use Stripe\Stripe;
use Stripe\PaymentIntent;

use Illuminate\Support\Facades\DB;
class PaymentController extends Controller
{
    public function index()
    {
        try {
            // Eager load the user relationship
            $payments = Payment::with('user')->get();
            return view('inquiry.online_payment', compact('payments'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to retrieve payments: ' . $e->getMessage());
        }
    }

    // public function history()
    // {
    //     $payments = Payment::where('parent_id', auth()->id())->with('package', 'session')->get();
    //     return view('payments.history', compact('payments'));
    // }

    public function create(Request $request)
    {
        $packages = Package::all();
        $sessions = Session::all();
        return view('payments.create', compact('packages', 'sessions'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parent_first_name' => 'nullable|string',
            'parent_last_name' => 'nullable|string',
            'parent_phone' => 'nullable|numeric',
            'parent_email' => 'nullable',
            'student_first_name' => 'nullable|string',
            'student_last_name' => 'nullable|string',
            'email' => 'nullable|email',
            'payment_type' => 'nullable|string',
            'payment_amount' => 'nullable|numeric',
            'note' => 'nullable|string',
            'cardholder_name' => 'nullable|string',
            'card_number' => 'nullable|string',
            'card_exp_date' => 'nullable|string',
            'cvv' => 'nullable|string',
            'bill_address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zip_code' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $payment = DB::transaction(function () use ($request) {
                return Payment::create([
                    'parent_first_name' => $request->parent_first_name,
                    'parent_last_name' => $request->parent_last_name,
                    'parent_phone' => $request->parent_phone,
                    'parent_email' => $request->parent_email,
                    'student_first_name' => $request->student_first_name,
                    'student_last_name' => $request->student_last_name,
                    'email' => $request->email,
                    'payment_type' => $request->payment_type,
                    'payment_amount' => $request->payment_amount,
                    'note' => $request->note,
                    'cardholder_name' => $request->cardholder_name,
                    'card_number' => substr($request->card_number, -4), // last 4 only
                    'card_exp_date' => encrypt($request->card_exp_date),
                    'cvv' => encrypt($request->cvv),
                    'bill_address' => $request->bill_address,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip_code' => $request->zip_code,
                ]);
            });

            $bccEmails = ['dev@bugletech.com', 'ravi.kamdar@bugletech.com'];

            // Queue both student and admin emails
            Mail::to($payment->parent_email)
                ->queue(new PaymentConfirmationMail($payment));

            Mail::to(['ben.hartman@zoffnesscollegeprep.com', 'info@zoffnesscollegeprep.com'])
                ->bcc($bccEmails)
                ->queue(new PaymentConfirmationMail($payment, true));

            return response()->json([
                'success' => true,
                'message' => 'Payment recorded and confirmation email sent.',
                'data' => $payment
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Payment storing failed: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'exception' => $e,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $payment = Payment::findOrFail($id);  // Find the payment to edit
        $packages = Package::all();  // All available packages
        $sessions = Session::all();  // All available sessions

        return view('payments.edit', compact('payment', 'packages', 'sessions'));
    }
    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'payment_method' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'package_id' => 'nullable|exists:packages,id',
            'session_id' => 'nullable|exists:sessions,id',
        ]);

        // Find the payment to update
        $payment = Payment::findOrFail($id);

        // Update payment details
        $payment->update([
            'payment_method' => $request->payment_method,
            'amount' => $request->amount,
            'package_id' => $request->package_id,
            'session_id' => $request->session_id,
            'status' => 'Pending',  // Set status to pending while editing
        ]);

        // Simulate payment processing (you may integrate a real payment gateway here)
        $payment->status = 'Completed';  // Example: Payment successful
        $payment->transaction_id = 'TXN' . time();  // Random transaction ID
        $payment->save();

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }

    // In PaymentController

    public function show($id)
    {
        // Eager load the related 'student', 'package', and 'session' relationships
        $payment = Payment::with(['student', 'package', 'session'])->findOrFail($id);

        // Return the view with the payment, student, package, and session
        return view('payments.view', compact('payment'));
    }

    public function createPaymentIntent(Request $request)
    {
        try {
            // 🔹 Normalize inconsistent keys before validation
            $request->merge([
                'student_first_name' => $request->input('student_first_name') ?? $request->input('student_firstname'),
                'student_last_name' => $request->input('student_last_name') ?? $request->input('student_lastname'),
                'parent_first_name' => $request->input('parent_first_name') ?? $request->input('parent_firstname'),
                'parent_last_name' => $request->input('parent_last_name') ?? $request->input('parent_lastname'),

                // ✅ Normalize exam_date and date into one field
                'exam_date' => $request->input('exam_date') ?? $request->input('date'),
            ]);

            // 🔹 Validate input
            $validated = $request->validate([
                'amount' => 'required|integer',
                'currency' => 'nullable|string|in:usd,eur,inr',
                'description' => 'nullable|string',
                'receipt_email' => 'nullable|email',
                'student_first_name' => 'nullable|string',
                'student_last_name' => 'nullable|string',
                'parent_first_name' => 'nullable|string',
                'parent_last_name' => 'nullable|string',
                'parent_email' => 'nullable|email',
                'student_email' => 'nullable|email',
                'parent_phone' => 'nullable|string',
                'school' => 'nullable|string',
                'graduation_year' => 'nullable|string',
                'exam_date' => 'nullable|array',     // ✅ accepts array for exam_date/date
                'exam_date.*' => 'nullable|string',    // ✅ each value should be string
            ]);

            Stripe::setApiKey(env('STRIPE_SECRET'));
            Stripe::setApiVersion('2025-09-30.clover');

            // 🔹 Extract test dates safely
            $testDates = $validated['exam_date'] ?? [];
            $formattedTestDates = json_encode($testDates); // ✅ JSON encode for Stripe metadata

            // 🔹 Metadata
            $metadata = [
                'student_first_name' => $validated['student_first_name'] ?? '',
                'student_last_name' => $validated['student_last_name'] ?? '',
                'parent_first_name' => $validated['parent_first_name'] ?? '',
                'parent_last_name' => $validated['parent_last_name'] ?? '',
                'parent_email' => $validated['parent_email'] ?? '',
                'student_email' => $validated['student_email'] ?? '',
                'parent_phone' => $validated['parent_phone'] ?? '',
                'school' => $validated['school'] ?? '',
                'graduation_year' => $validated['graduation_year'] ?? '',
                'test_date' => $formattedTestDates, // ✅ stored as JSON string in Stripe
            ];

            $currency = $validated['currency'] ?? 'usd';
            $description = $validated['description'] ?? 'Zoffness Payment';
            // $receiptEmail = $validated['receipt_email'] ?? $validated['parent_email'] ?? null;

            $paymentIntent = PaymentIntent::create([
                'amount' => $validated['amount'],
                'currency' => $currency,
                'description' => $description,
                // 'receipt_email' => $receiptEmail,
                'metadata' => $metadata,
                'automatic_payment_methods' => ['enabled' => true],
            ]);

            return response()->json([
                'success' => true,
                'clientSecret' => $paymentIntent->client_secret,
                'payment_id' => $paymentIntent->id,
                'metadata' => $metadata,
                'test_date' => $testDates, // ✅ return parsed array to frontend
            ]);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }


 
}
