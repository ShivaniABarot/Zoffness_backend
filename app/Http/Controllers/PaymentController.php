<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Package;
use App\Models\Session;
use App\Models\Student;
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
        $request->validate([
            'payment_method' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'package_id' => 'nullable|exists:packages,id',
            'session_id' => 'nullable|exists:sessions,id',
        ]);

        // Create payment record
        $payment = Payment::create([
            'parent_id' => auth()->id(),
            'package_id' => $request->package_id,
            'session_id' => $request->session_id,
            'payment_method' => $request->payment_method,
            'amount' => $request->amount,
            'status' => 'Pending',
        ]);

        // Simulate payment process (for now)
        $payment->status = 'Completed';
        $payment->transaction_id = 'TXN' . time();
        $payment->save();

        return redirect()->route('payments.index')->with('success', 'Payment completed successfully.');
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
