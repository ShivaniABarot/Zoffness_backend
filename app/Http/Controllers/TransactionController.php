<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;
use Stripe\Exception\ApiErrorException;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // Set Stripe API key
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Initialize filter parameters
        $filter = $request->get('filter', 'last_week');
        $statusFilter = $request->get('status', 'all');
        $startDate = Carbon::now()->subDays(7)->startOfDay()->timestamp;
        $endDate = Carbon::now()->endOfDay()->timestamp;

        // Adjust date range based on filter
        try {
            switch ($filter) {
                case 'today':
                    $startDate = Carbon::today()->startOfDay()->timestamp;
                    break;
                case 'last_month':
                    $startDate = Carbon::now()->subDays(30)->startOfDay()->timestamp;
                    break;
                case 'custom':
                    if ($request->filled(['from_date', 'to_date'])) {
                        $startDate = Carbon::parse($request->from_date)->startOfDay()->timestamp;
                        $endDate = Carbon::parse($request->to_date)->endOfDay()->timestamp;
                    } else {
                        // Fallback to default if custom dates are invalid
                        Log::warning('Invalid custom date range provided', [
                            'from_date' => $request->from_date,
                            'to_date' => $request->to_date,
                        ]);
                    }
                    break;
            }

            // Fetch PaymentIntents from Stripe
            $params = [
                'limit' => 100,
                'created' => ['gte' => $startDate, 'lte' => $endDate],
            ];

            $paymentIntents = PaymentIntent::all($params);

            // Format transactions for the view
            $transactions = collect($paymentIntents->data)->map(function ($txn) {
                $amount = $txn->amount_received > 0 ? $txn->amount_received : $txn->amount;
                $status = $txn->status ?? 'unknown';
                // dd($txn);
                // Default values
                $customerName = 'Guest';
                $customerEmail = null;

                // Convert metadata object to array
                $metadata = !empty($txn->metadata) ? $txn->metadata->toArray() : [];
                // dd($metadata);
                if (!empty($metadata['parent_first_name']) || !empty($metadata['parent_last_name'])) {
                    $customerName = trim(($metadata['parent_first_name'] ?? '') . ' ' . ($metadata['parent_last_name'] ?? ''));
                    $customerEmail = $metadata['parent_email'] ?? null;
                } elseif (!empty($txn->customer)) {
                    try {
                        $customer = Customer::retrieve($txn->customer);
                        $customerName = $customer->metadata->parent_first_name ?? 'Guest';
                        $customerEmail = $customer->email ?? null;
                    } catch (\Exception $e) {
                        \Log::error('Failed to retrieve customer for transaction', [
                            'transaction_id' => $txn->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
                // dd($txn);
                // ✅ PRIORITY 3: Fallback — if still no name found, check if metadata has student info
                if ($customerName === 'Guest' && !empty($metadata['student_first_name'])) {
                    $customerName = trim(($metadata['student_first_name'] ?? '') . ' ' . ($metadata['student_last_name'] ?? ''));
                    $customerEmail = $customerEmail ?? ($metadata['student_email'] ?? null);
                }

                return (object) [
                    'id' => $txn->id,
                    'customer_name' => $customerName,
                    'customer_email' => $customerEmail,
                    'amount' => $amount,
                    'status' => $status,
                    'created' => $txn->created,
                ];
            });



            // Apply status filter
            if ($statusFilter !== 'all') {
                $transactions = $transactions->filter(function ($txn) use ($statusFilter) {
                    return strtolower($txn->status) === strtolower($statusFilter);
                });
            }

            return view('transactions.index', [
                'transactions' => $transactions,
                'filter' => $filter,
                'statusFilter' => $statusFilter,
                'from_date' => $request->from_date ?? null,
                'to_date' => $request->to_date ?? null,
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching transactions', [
                'error' => $e->getMessage(),
                'params' => $params ?? [],
            ]);
            return back()->with('error', 'Unable to fetch transactions. Please try again later.');
        }
    }

    public function show($id)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $transaction = PaymentIntent::retrieve($id);

            // Convert metadata to plain array
            $metadata = !empty($transaction->metadata) ? $transaction->metadata->toArray() : [];

            // Customer info from metadata
            $customerName = $metadata['parent_first_name'] ?? 'Guest';
            $customerEmail = $metadata['parent_email'] ?? null;
            $customerPhone = $metadata['parent_phone'] ?? null;

            // ✅ Get Payment Type from metadata
            $paymentType = $metadata['payment_type']
                ?? $metadata['package_name']
                ?? $metadata['test_type']
                ?? 'N/A';

            // Fallback to Stripe customer if metadata missing
            if ((!$customerEmail || $customerName === 'Guest') && !empty($transaction->customer)) {
                try {
                    $customer = Customer::retrieve($transaction->customer);
                    $customerName = $customer->name ?? $customerName;
                    $customerEmail = $customer->email ?? $customerEmail;
                } catch (\Exception $e) {
                    Log::error('Failed to retrieve customer for transaction', [
                        'transaction_id' => $id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            return view('transactions.show', [
                'transaction' => $transaction,
                'customer_name' => $customerName,
                'customer_email' => $customerEmail,
                'customer_phone' => $customerPhone,
                'payment_type' => $paymentType, // ✅ Pass payment type
                'metadata' => $metadata,
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching transaction details', [
                'transaction_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Unable to fetch transaction details. Please try again later.');
        }
    }


    public function export(Request $request)
{
    $filter = $request->get('filter', 'last_week');
    $status = $request->get('status', 'all');
    $from_date = $request->get('from_date');
    $to_date = $request->get('to_date');

    $fileName = 'transactions_' . now()->format('Y_m_d_His') . '.xlsx';
    return Excel::download(new TransactionsExport($filter, $status, $from_date, $to_date), $fileName);
}


}