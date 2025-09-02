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
        'parent_first_name' => 'nullable|string|max:255',
        'parent_last_name'  => 'nullable|string|max:255',
        'parent_phone'      => 'nullable|numeric',
        'student_first_name'=> 'nullable|string|max:255',
        'student_last_name' => 'nullable|string|max:255',
        'email'             => 'nullable|email|max:255',
        'payment_type'      => 'nullable|string|max:100',
        'payment_amount'    => 'nullable|numeric|min:0',
        'note'              => 'nullable|string',
        'cardholder_name'   => 'nullable|string|max:255',
        'card_number'       => 'nullable|string|max:20',
        'card_exp_date'     => 'nullable|string|max:10',
        'cvv'               => 'nullable|string|max:4',
        'bill_address'      => 'nullable|string|max:255',
        'city'              => 'nullable|string|max:100',
        'state'             => 'nullable|string|max:100',
        'zip_code'          => 'nullable|string|max:20',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors'  => $validator->errors()
        ], 422);
    }

    try {
        $payment = DB::transaction(function () use ($request) {
            return Payment::create([
                'parent_first_name' => $request->parent_first_name,
                'parent_last_name'  => $request->parent_last_name,
                'parent_phone'      => $request->parent_phone,
                'student_first_name'=> $request->student_first_name,
                'student_last_name' => $request->student_last_name,
                'email'             => $request->email,
                'payment_type'      => $request->payment_type,
                'payment_amount'    => $request->payment_amount,
                'note'              => $request->note,
                'cardholder_name'   => $request->cardholder_name,
                'card_number'       => substr($request->card_number, -4), // last 4 only
                'card_exp_date'     => encrypt($request->card_exp_date),
                'cvv'               => encrypt($request->cvv),
                'bill_address'      => $request->bill_address,
                'city'              => $request->city,
                'state'             => $request->state,
                'zip_code'          => $request->zip_code,
            ]);
        });

        $bccEmails = ['dev@bugletech.com', 'ravi.kamdar@bugletech.com'];

        // Queue both student and admin emails
        Mail::to($payment->email)
            ->queue(new PaymentConfirmationMail($payment));

        Mail::to(['ben.hartman@zoffnesscollegeprep.com', 'info@zoffnesscollegeprep.com'])
            ->bcc($bccEmails)
            ->queue(new PaymentConfirmationMail($payment, true));

        return response()->json([
            'success' => true,
            'message' => 'Payment recorded and confirmation email sent.',
            'data'    => $payment
        ], 201);

    } catch (\Exception $e) {
        \Log::error('Payment storing failed: ' . $e->getMessage(), [
            'request_data' => $request->all(),
            'exception'    => $e,
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


    // public function createPaymentIntent(Request $request)
    // {
    //     try {
    //         // 🔹 Normalize inconsistent keys before validation
    //         $request->merge([
    //             'student_first_name' => $request->input('student_first_name') ?? $request->input('student_firstname'),
    //             'student_last_name'  => $request->input('student_last_name') ?? $request->input('student_lastname'),
    //             'parent_first_name'  => $request->input('parent_first_name') ?? $request->input('parent_firstname'),
    //             'parent_last_name'   => $request->input('parent_last_name') ?? $request->input('parent_lastname'),
    //         ]);
    
    //         // 🔹 Validate input
    //         $validated = $request->validate([
    //             'amount'        => 'required|integer|min:50', 
    //             'currency'      => 'nullable|string|in:usd,eur,inr',
    //             'description'   => 'nullable|string',
    //             'receipt_email' => 'nullable|email',
    //             'student_first_name' => 'nullable|string',
    //             'student_last_name'  => 'nullable|string',
    //             'parent_first_name'  => 'nullable|string',
    //             'parent_last_name'   => 'nullable|string',
    //             'parent_email'       => 'nullable|email',
    //             'student_email'      => 'nullable|email',
    //             'parent_phone'       => 'nullable|string',
    //             'school'             => 'nullable|string',
    //             'graduation_year'    => 'nullable|string',
    //         ]);
    
    //         \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    
    //         // 🔹 Build metadata centrally (no frontend duplication)
    //         $metadata = [
    //             'student_name'   => trim(($validated['student_first_name'] ?? '') . ' ' . ($validated['student_last_name'] ?? '')) ?: '',
    //             'parent_name'    => trim(($validated['parent_first_name'] ?? '') . ' ' . ($validated['parent_last_name'] ?? '')) ?: '',
    //             'parent_email'   => $validated['parent_email'] ?? '',
    //             'student_email'  => $validated['student_email'] ?? '',
    //             'parent_phone'   => $validated['parent_phone'] ?? '',
    //             'school'         => $validated['school'] ?? '',
    //             'graduation_year'=> $validated['graduation_year'] ?? '',
    //             'payment_date'   => now()->toDateString(),
    //         ];
    
    //         $currency     = $validated['currency'] ?? 'usd';
    //         $description  = $validated['description'] ?? 'Zoffness Payment';
    //         $receiptEmail = $validated['receipt_email'] ?? $validated['parent_email'] ?? null;
    
    //         $paymentIntent = \Stripe\PaymentIntent::create([
    //             'amount'   => $validated['amount'],
    //             'currency' => $currency,
    //             'description' => $description,
    //             'receipt_email' => $receiptEmail,
    //             'metadata' => $metadata,
    //             'automatic_payment_methods' => ['enabled' => true],
    //         ]);
    
    //         return response()->json([
    //             'success'      => true,
    //             'clientSecret' => $paymentIntent->client_secret,
    //             'payment_id'   => $paymentIntent->id,
    //             'metadata'     => $metadata, // return for frontend display
    //         ]);
    
    //     } catch (\Stripe\Exception\ApiErrorException $e) {
    //         return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
    //     } catch (\Exception $e) {
    //         return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    //     }
    // }
    


    public function createPaymentIntent(Request $request)
{
    try {
        // 🔹 Normalize inconsistent keys before validation
        $request->merge([
            'student_first_name' => $request->input('student_first_name') ?? $request->input('student_firstname'),
            'student_last_name'  => $request->input('student_last_name') ?? $request->input('student_lastname'),
            'parent_first_name'  => $request->input('parent_first_name') ?? $request->input('parent_firstname'),
            'parent_last_name'   => $request->input('parent_last_name') ?? $request->input('parent_lastname'),
        ]);

        // 🔹 Validate input
        $validated = $request->validate([
            'amount'        => 'required|integer', 
            'currency'      => 'nullable|string|in:usd,eur,inr',
            'description'   => 'nullable|string',
            'receipt_email' => 'nullable|email',
            'student_first_name' => 'nullable|string',
            'student_last_name'  => 'nullable|string',
            'parent_first_name'  => 'nullable|string',
            'parent_last_name'   => 'nullable|string',
            'parent_email'       => 'nullable|email',
            'student_email'      => 'nullable|email',
            'parent_phone'       => 'nullable|string',
            'school'             => 'nullable|string',
            'graduation_year'    => 'nullable|string',
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        // 🔹 Build metadata centrally with consistent naming
        $metadata = [
            'student_name'   => trim(($validated['student_first_name'] ?? '') . ' ' . ($validated['student_last_name'] ?? '')) ?: '',
            'parent_name'    => trim(($validated['parent_first_name'] ?? '') . ' ' . ($validated['parent_last_name'] ?? '')) ?: '',
            'parent_email'   => $validated['parent_email'] ?? '',
            'student_email'  => $validated['student_email'] ?? '',
            'parent_phone'   => $validated['parent_phone'] ?? '',
            'school'         => $validated['school'] ?? '',
            'graduation_year' => $validated['graduation_year'] ?? '',
            'payment_date'   => now()->toDateString(),
        ];

        $currency     = $validated['currency'] ?? 'usd';
        $description  = $validated['description'] ?? 'Zoffness Payment';
        $receiptEmail = $validated['receipt_email'] ?? $validated['parent_email'] ?? null;

        $paymentIntent = PaymentIntent::create([
            'amount'   => $validated['amount'],
            'currency' => $currency,
            'description' => $description,
            'receipt_email' => $receiptEmail,
            'metadata' => $metadata,
            'automatic_payment_methods' => ['enabled' => true],
        ]);

        return response()->json([
            'success'      => true,
            'clientSecret' => $paymentIntent->client_secret,
            'payment_id'   => $paymentIntent->id,
            'metadata'     => $metadata, // Return for frontend display
        ]);

    } catch (\Stripe\Exception\ApiErrorException $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}
    
}
