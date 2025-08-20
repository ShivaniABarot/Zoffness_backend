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



    // public function store(Request $request)
    // {
    //     if (!Auth::check()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'User is not authenticated.',
    //         ], 401);
    //     }

    //     $validator = Validator::make($request->all(), [
    //         'student_first_name' => 'nullable|string',
    //         'student_last_name'  => 'nullable|string',
    //         'email'              => 'nullable|email',
    //         'payment_type'       => 'nullable|string',
    //         'payment_amount'     => 'nullable|numeric|min:0',
    //         'note'               => 'nullable|string',
    //         'cardholder_name'    => 'nullable|string',
    //         'card_number'        => 'nullable|string',
    //         'card_exp_date'      => 'nullable|string',
    //         'cvv'                => 'nullable|string',
    //         'bill_address'       => 'nullable|string',
    //         'city'               => 'nullable|string',
    //         'state'              => 'nullable|string',
    //         'zip_code'           => 'nullable|string',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validation failed',
    //             'errors'  => $validator->errors()
    //         ], 422);
    //     }

    //     try {
    //         $userId = Auth::id();
    //         \Log::info('User ID: ' . $userId);

    //         $payment = DB::transaction(function () use ($request, $userId) {
    //             return Payment::create([
    //                 'student_first_name' => $request->student_first_name,
    //                 'student_last_name'  => $request->student_last_name,
    //                 'email'              => $request->email,
    //                 'payment_type'       => $request->payment_type,
    //                 'payment_amount'     => $request->payment_amount,
    //                 'note'               => $request->note,
    //                 'cardholder_name'    => $request->cardholder_name,
    //                 'card_number'        => substr($request->card_number, -4), // Store only last 4 digits
    //                 'card_exp_date'      => encrypt($request->card_exp_date),
    //                 'cvv'                => encrypt($request->cvv),
    //                 'bill_address'       => $request->bill_address,
    //                 'city'               => $request->city,
    //                 'state'              => $request->state,
    //                 'zip_code'           => $request->zip_code,
    //                 'user_id'            => $userId,
    //             ]);
    //         });

    //         // Fetch parent (logged-in user) details
    //         $parent = User::select('firstname', 'lastname', 'email', 'phone_no')->find($userId);

    //         $studentName = trim($request->student_first_name . ' ' . $request->student_last_name);
    //         $billingDetails = [
    //             'amount'        => $request->payment_amount,
    //             'payment_type'  => $request->payment_type,
    //             'payment_date'  => now()->format('m-d-Y'),
    //             'address'       => $request->bill_address,
    //             'city'          => $request->city,
    //             'state'         => $request->state,
    //             'zip_code'      => $request->zip_code,
    //             'email'         => $request->email,
    //             'note'          => $request->note ?? 'No note provided',
    //             'parent_name'   => $parent ? ($parent->firstname . ' ' . $parent->lastname) : 'N/A',
    //             'parent_email'  => $parent ? $parent->email : 'N/A',
    //             'phone_no'      => $parent ? $parent->phone_no : 'N/A',
    //             'cardholder'    => $request->cardholder_name,
    //             'last4'         => substr($request->card_number, -4),
    //         ];

    //         // Define BCC emails
    //         $bccEmails = ['dev@bugletech.com', 'ravi.kamdar@bugletech.com'];

    //         // Send emails
    //         Mail::to($request->email)
    //             ->send(new PaymentConfirmationMail($studentName, $billingDetails));

    //         Mail::to(['ben.hartman@zoffnesscollegeprep.com', 'info@zoffnesscollegeprep.com'])
    //             ->bcc($bccEmails)
    //             ->queue(new PaymentConfirmationMail($studentName, $billingDetails, true));

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Payment recorded and confirmation email sent.',
    //             'data'    => $payment,
    //             'user'    => $parent // Include user details in response
    //         ], 201);

    //     } catch (\Exception $e) {
    //         \Log::error('Payment storing failed: ' . $e->getMessage(), [
    //             'request_data' => $request->all(),
    //             'exception' => $e,
    //         ]);

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Something went wrong: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }



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



}
