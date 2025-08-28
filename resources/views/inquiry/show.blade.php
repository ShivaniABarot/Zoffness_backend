@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">{{ $title }}</h1>

    <div class="card shadow-sm rounded-3">
        <div class="card-body">
            <h5 class="card-title">Parent Details</h5>
            <p><strong>Parent Name:</strong> {{ ($item->parent_first_name ?? $item->parent_firstname ?? '') . ' ' . ($item->parent_last_name ?? $item->parent_lastname ?? '') }}</p>
            <p><strong>Email:</strong> {{ $item->parent_email ?? $item->student_email ?? $item->email ?? 'N/A' }}</p>
            <p><strong>Phone:</strong> {{ $item->parent_phone ?? '' }}</p>

            <h5 class="mt-4">Student Details</h5>
            <p><strong>Name:</strong> {{ ($item->student_first_name ?? $item->student_firstname ?? '') . ' ' . ($item->student_last_name ?? $item->studentlastname ?? '') }}</p>
            <p><strong>Email:</strong> {{ $item->student_email ?? 'N/A' }}</p>

            <h5 class="mt-4">Booking Info</h5>
            <p><strong>Booking Type:</strong> {{ $item->display_booking_type }}</p>
            <p><strong>Package:</strong> {{ $item->payment_type ?? $item->package_name ?? $item->booking_type ?? $item->package_type ?? $item->packages ?? $item->test_type ?? 'N/A' }}</p>
            <p><strong>Date:</strong> 
    {{ $item->exam_date 
        ? \Carbon\Carbon::parse($item->exam_date)->format('d-m-Y') 
        : ($item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d-m-Y H:i') : 'N/A') 
    }}
</p>

            <p><strong>Payment Amount:</strong> 
                @php
                    $amountValue = $item->amount ?? $item->payment_amount ?? $item->subtotal ?? $item->sessions;
                @endphp
                {{ is_numeric($amountValue) ? number_format($amountValue, 2) : ($amountValue ?? 'N/A') }}
            </p>
            <p><strong>Status:</strong> {{ ucfirst($item->status ?? '') }}</p>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('inquiry.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>
@endsection
