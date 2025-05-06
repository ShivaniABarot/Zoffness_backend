<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class ExamController extends Controller
{
    public function showForm()
    {
        return view('examform');
    }

    public function createCheckoutSession(Request $request)
    {
        // Set Stripe Secret Key
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Create a Checkout Session
            $checkoutSession = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Exam Registration Fee',
                        ],
                        'unit_amount' => $request->input('total') * 100, // Convert to cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('payment.success'),
                'cancel_url' => route('payment.cancel'),
            ]);

            // Redirect to Stripe Checkout
            return redirect()->away($checkoutSession->url);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error: ' . $e->getMessage());
        }
    }

    public function success()
    {
        return view('payment.success');
    }

    public function cancel()
    {
        return view('payment.cancel');
    }
}
