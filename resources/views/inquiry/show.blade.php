@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">{{ $title }}</h1>

        <div class="card shadow-sm rounded-3">
            <div class="card-body">

                <!-- Parent Details -->
                <h5 class="card-title mb-3">👨‍👩‍👧 Parent Details</h5>
                <div class="row mb-2">
                    <div class="col-md-4"><strong>Parent Name:</strong></div>
                    <div class="col-md-8">
                        {{ ($item->parent_first_name ?? $item->parent_firstname ?? '') . ' ' . ($item->parent_last_name ?? $item->parent_lastname ?? '') }}
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4"><strong>Email:</strong></div>
                    <div class="col-md-8">{{ $item->parent_email ?? $item->student_email ?? $item->email ?? 'N/A' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4"><strong>Phone:</strong></div>
                    <div class="col-md-8">{{ $item->parent_phone ?? 'N/A' }}</div>
                </div>

                <hr>

                <!-- Student Details -->
                <h5 class="card-title mb-3">🎓 Student Details</h5>
                <div class="row mb-2">
                    <div class="col-md-4"><strong>Name:</strong></div>
                    <div class="col-md-8">
                        {{ ($item->student_first_name ?? $item->student_firstname ?? '') . ' ' . ($item->student_last_name ?? $item->studentlastname ?? '') }}
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4"><strong>Email:</strong></div>
                    <div class="col-md-8">{{ $item->student_email ?? 'N/A' }}</div>
                </div>

                <hr>

                <!-- Booking Info -->
                <h5 class="card-title mb-3">📅 Booking Info</h5>
                <div class="row mb-2">
                    <div class="col-md-4"><strong>Package:</strong></div>
                    <div class="col-md-8">
                        {{ $item->payment_type ?? $item->package_name ?? $item->booking_type ?? $item->package_type ?? $item->packages ?? $item->test_type ?? $item->selected_package ?? 'N/A' }}
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4"><strong>Date:</strong></div>
                    <div class="col-md-8">
                        {{ $item->exam_date
        ? \Carbon\Carbon::parse($item->exam_date)->format('d-m-Y')
        : ($item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d-m-Y H:i') : 'N/A') 
                        }}
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4"><strong>Payment Amount:</strong></div>
                    <div class="col-md-8">
                        @php
                            $amountValue = $item->amount ?? $item->payment_amount ?? $item->subtotal ?? $item->sessions;
                        @endphp
                        {{ is_numeric($amountValue) ? number_format($amountValue, 2) : ($amountValue ?? 'N/A') }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4"><strong>Status:</strong></div>
                    <div class="col-md-8">
                        <span class="badge {{ ($item->status ?? '') === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ ucfirst($item->status ?? 'N/A') }}
                        </span>
                    </div>
                </div>

            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('inquiry.index') }}" class="btn btn-secondary">⬅ Back</a>
        </div>
    </div>
@endsection