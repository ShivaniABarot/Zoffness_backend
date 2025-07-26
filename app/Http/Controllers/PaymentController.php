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
use Illuminate\Support\Facades\DB;
class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('student', 'package', 'session')->get();
        return view('payments.index', compact('payments'));
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

        Stripe::setApiKey(env('VITE_STRIPE_SECRET_KEY'));
        
        $validator = Validator::make($request->all(), [
            'student_first_name' => 'string',
            'student_last_name'  => 'string',
            'email'              => 'email',
            'payment_type'       => 'string',
            'payment_amount'     => 'numeric',
            'note'               => 'nullable|string',
            'cardholder_name'    => 'string',
            'card_number'        => 'string',
            'card_exp_date'      => 'string',
            'cvv'                => 'string',
            'bill_address'       => 'string',
            'city'               => 'string',
            'state'              => 'string',
            'zip_code'           => 'string',
            'user_id'            => 'exists:users,id',
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
                    'student_first_name' => $request->student_first_name,
                    'student_last_name'  => $request->student_last_name,
                    'email'              => $request->email,
                    'payment_type'       => $request->payment_type,
                    'payment_amount'     => $request->payment_amount,
                    'note'               => $request->note,
                    'cardholder_name'    => $request->cardholder_name,
                    'card_number'        => substr($request->card_number, -4),
                    'card_exp_date'      => encrypt($request->card_exp_date),
                    'cvv'                => encrypt($request->cvv),
                    'bill_address'       => $request->bill_address,
                    'city'               => $request->city,
                    'state'              => $request->state,
                    'zip_code'           => $request->zip_code,
                    'user_id'            => $request->user_id,
                ]);
            });

            // Fetch parent details from users table
            $parent = User::where('id', $request->user_id)->select('firstname', 'lastname', 'email' ,'phone_no')->first();

            // Prepare email data
            $studentName = $request->student_first_name . ' ' . $request->student_last_name;
            $billingDetails = [
                'cardholder'    => $request->cardholder_name,
                'last4'         => substr($request->card_number, -4),
                'payment_type'  => $request->payment_type,
                'amount'        => $request->payment_amount,
                'payment_date'  => now()->format('m-d-Y'),
                'address'       => $request->bill_address,
                'city'          => $request->city,
                'state'         => $request->state,
                'zip_code'      => $request->zip_code,
                'email'         => $request->email,
                'note'          => $request->note ?? 'No note provided',
                'parent_name'   => $parent ? ($parent->firstname . ' ' . $parent->lastname) : 'N/A',
                'parent_email'  => $parent ? $parent->email : 'N/A',
                'phone_no'  => $parent ? $parent->phone_no : 'N/A',
            ];

            // Send confirmation to payer
            Mail::to($request->email)->send(new PaymentConfirmationMail($studentName, $billingDetails));

            // Optionally, send to admin
            Mail::to(['dev@bugletech.com'])->queue(new PaymentConfirmationMail(
                $studentName,
                $billingDetails,
                true // admin flag
            ));

            return response()->json([
                'success' => true,
                'message' => 'Payment recorded and confirmation email sent.',
                'data'    => $payment
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



}
