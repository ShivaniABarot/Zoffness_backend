@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h3>Transaction Details</h3>

        <!-- <div class="card p-3 mb-3">
                <h5>Customer Details</h5>
                <p><strong>Name:</strong> {{ $customer_name }}</p>
                <p><strong>Email:</strong> {{ $customer_email }}</p>
                <p><strong>Phone:</strong> {{ $customer_phone }}</p>
            </div> -->

        <div class="card p-3 mb-3">
            <h5>Customer Details</h5>
            @foreach($metadata as $key => $value)
                <p><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</p>
            @endforeach
        </div>

        <div class="card p-3">
            <h5>Transaction Info</h5>
            <p><strong>ID:</strong> {{ $transaction->id }}</p>
            <p><strong>Amount:</strong> ${{ number_format($transaction->amount_received / 100, 2) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($transaction->status) }}</p>
            <p><strong>Date:</strong> {{ \Carbon\Carbon::createFromTimestamp($transaction->created)->format('M d Y') }}</p>
            <p><strong>Time:</strong> {{ \Carbon\Carbon::createFromTimestamp($transaction->created)->format('H:i A') }}</p>

            <div class="d-flex justify-content-center mt-3">
                <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left-circle" style="font-size: 0.9rem;"></i> Back
                </a>
            </div>




        </div>


    </div>
@endsection