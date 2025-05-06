@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4 text-center">Payment Details</h1>

        <div class="row">
            <!-- Payment Information -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-light">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Payment Information</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Payment ID:</strong> {{ $payment->id }}</li>
                            <li class="list-group-item"><strong>Amount:</strong> ${{ number_format($payment->amount, 2) }}</li>
                            <li class="list-group-item"><strong>Payment Method:</strong> {{ $payment->payment_method }}</li>
                            <li class="list-group-item"><strong>Status:</strong> 
                                <span class="badge 
                                    @if($payment->status == 'Completed') bg-success 
                                    @elseif($payment->status == 'Pending') bg-warning 
                                    @elseif($payment->status == 'Failed') bg-danger 
                                    @else bg-secondary @endif">
                                    {{ $payment->status }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Package Information -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-light">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Package Information</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Package Name:</strong> {{ $payment->package->name }}</li>
                            <li class="list-group-item"><strong>Price:</strong> ${{ number_format($payment->package->price, 2) }}</li>
                            <li class="list-group-item"><strong>Number of Sessions:</strong> {{ $payment->package->number_of_sessions }}</li>
                            <li class="list-group-item"><strong>Description:</strong> {{ $payment->package->description ?? 'N/A' }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Session Information -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-light">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Session Information</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Session Title:</strong> {{ $payment->session->title }}</li>
                            <li class="list-group-item"><strong>Session Type:</strong> {{ ucfirst($payment->session->session_type) }}</li>
                            <li class="list-group-item"><strong>Date:</strong> {{ \Carbon\Carbon::parse($payment->session->date)->format('M d, Y') }}</li>
                            <li class="list-group-item"><strong>Time Slot:</strong> {{ $payment->session->time_slot }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Extra section to improve overall appearance -->
            <div class="col-12">
                <div class="d-flex justify-content-center mt-4">
                    <a href="{{ route('payments.index') }}" class="btn btn-secondary btn-lg">Back to Payments</a>
                </div>
            </div>
        </div>
    </div>
@endsection
