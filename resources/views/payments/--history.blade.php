@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Payment History</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Package</th>
                <th>Session</th>
                <th>Method</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Transaction ID</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
                <tr>
                    <td>{{ $payment->id }}</td>
                    <td>{{ $payment->package->name ?? 'N/A' }}</td>
                    <td>{{ $payment->session->title ?? 'N/A' }}</td>
                    <td>{{ $payment->payment_method }}</td>
                    <td>${{ $payment->amount }}</td>
                    <td>{{ $payment->status }}</td>
                    <td>{{ $payment->transaction_id }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No payments found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
