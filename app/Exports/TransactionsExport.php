<?php

namespace App\Exports;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionsExport implements FromCollection, WithHeadings
{
    protected $filter;
    protected $status;
    protected $from_date;
    protected $to_date;

    public function __construct($filter, $status, $from_date, $to_date)
    {
        $this->filter = $filter;
        $this->status = $status;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }

    public function collection()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $startDate = Carbon::now()->subDays(7)->startOfDay()->timestamp;
        $endDate = Carbon::now()->endOfDay()->timestamp;

        switch ($this->filter) {
            case 'today':
                $startDate = Carbon::today()->startOfDay()->timestamp;
                break;
            case 'last_month':
                $startDate = Carbon::now()->subDays(30)->startOfDay()->timestamp;
                break;
            case 'custom':
                if ($this->from_date && $this->to_date) {
                    $startDate = Carbon::parse($this->from_date)->startOfDay()->timestamp;
                    $endDate = Carbon::parse($this->to_date)->endOfDay()->timestamp;
                }
                break;
        }

        $params = [
            'limit' => 100,
            'created' => ['gte' => $startDate, 'lte' => $endDate],
        ];

        $paymentIntents = PaymentIntent::all($params);

        $transactions = collect($paymentIntents->data)->map(function ($txn) {
            $metadata = !empty($txn->metadata) ? $txn->metadata->toArray() : [];
            $customerName = $metadata['parent_first_name'] ?? 'Guest';
            $customerEmail = $metadata['parent_email'] ?? null;
            $amount = $txn->amount_received > 0 ? $txn->amount_received : $txn->amount;
            $status = $txn->status ?? 'unknown';

            return [
                'Transaction ID' => $txn->id,
                'Customer Name' => $customerName,
                'Customer Email' => $customerEmail,
                'Amount ($)' => number_format($amount / 100, 2),
                'Status' => ucfirst(str_replace('_', ' ', $status)),
                'Created Date' => Carbon::createFromTimestamp($txn->created)->format('d M Y H:i'),
            ];
        });

        if ($this->status !== 'all') {
            $transactions = $transactions->filter(function ($txn) {
                return strtolower($txn['Status']) === strtolower($this->status);
            });
        }

        return $transactions;
    }

    public function headings(): array
    {
        return [
            'Transaction ID',
            'Customer Name',
            'Customer Email',
            'Amount ($)',
            'Status',
            'Created Date',
        ];
    }
}
