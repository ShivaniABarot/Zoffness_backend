<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $endpointSecret = config('services.stripe.webhook_secret');
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);

            Log::info('Stripe webhook received', [
                'type' => $event->type,
                'id' => $event->id,
            ]);

            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $this->handlePaymentSucceeded($event->data->object);
                    break;

                case 'payment_intent.payment_failed':
                    $this->handlePaymentFailed($event->data->object);
                    break;

                case 'payment_intent.canceled':
                    $this->handlePaymentCanceled($event->data->object);
                    break;

                case 'charge.succeeded':
                    $this->handleChargeSucceeded($event->data->object);
                    break;

                default:
                    Log::info('Unhandled webhook event type', ['type' => $event->type]);
            }

            return response()->json(['status' => 'success'], 200);

        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid webhook payload', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Invalid webhook signature', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }
    }

    private function handlePaymentSucceeded($paymentIntent)
    {
        Log::info('Payment succeeded webhook', [
            'payment_intent_id' => $paymentIntent->id,
            'amount' => $paymentIntent->amount,
        ]);

        try {
            DB::transaction(function () use ($paymentIntent) {

                // Look for existing payment record
                $payment = Payment::where('stripe_payment_intent_id', $paymentIntent->id)->first();

                if (!$payment) {
                    // Create fallback payment record
                    $payment = Payment::create([
                        'stripe_payment_intent_id' => $paymentIntent->id,
                        'amount' => $paymentIntent->amount / 100,
                        'currency' => $paymentIntent->currency,
                        'status' => 'succeeded',
                        'metadata' => (array) $paymentIntent->metadata,
                        'paid_at' => now(),
                    ]);
                }

                // Update payment status
                $payment->update([
                    'status' => 'succeeded',
                    'paid_at' => now(),
                ]);
            });

        } catch (\Exception $e) {
            Log::error('Failed to process payment webhook', [
                'payment_intent_id' => $paymentIntent->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function handlePaymentFailed($paymentIntent)
    {
        Log::warning('Payment failed webhook', [
            'payment_intent_id' => $paymentIntent->id,
            'error' => $paymentIntent->last_payment_error->message ?? 'Unknown error',
        ]);

        Payment::where('stripe_payment_intent_id', $paymentIntent->id)
            ->update(['status' => 'failed']);
    }

    private function handlePaymentCanceled($paymentIntent)
    {
        Log::info('Payment canceled webhook', [
            'payment_intent_id' => $paymentIntent->id,
        ]);

        Payment::where('stripe_payment_intent_id', $paymentIntent->id)
            ->update(['status' => 'canceled']);
    }

    private function handleChargeSucceeded($charge)
    {
        $payment = Payment::where('stripe_payment_intent_id', $charge->payment_intent)->first();

        if ($payment) {
            $payment->update(['stripe_charge_id' => $charge->id]);
        }
    }
}
