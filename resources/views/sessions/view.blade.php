@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center rounded-top">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-calendar-check-fill me-2"></i>Session Details
                    </h4>
                </div>
                <div class="card-body px-4 py-4">

                    {{-- Session Title --}}
                    <div class="mb-4 d-flex align-items-center">
                        <i class="bi bi-journal-text text-primary me-2 fs-5"></i>
                        <div>
                            <strong>Session Title:</strong>
                            <div class="text-muted">{{ $session->title }}</div>
                        </div>
                    </div>

                    {{-- Session Type --}}
                    <div class="mb-4 d-flex align-items-center">
                        <i class="bi bi-ui-checks text-primary me-2 fs-5"></i>
                        <div>
                            <strong>Session Type:</strong>
                            <div class="text-muted text-capitalize">{{ str_replace('_', ' ', $session->session_type) }}</div>
                        </div>
                    </div>

                    {{-- Price Per Slot --}}
                    <div class="mb-4 d-flex align-items-center">
                        <i class="bi bi-currency-dollar text-primary me-2 fs-5"></i>
                        <div>
                            <strong>Price Per Slot:</strong>
                            <div class="text-muted">${{ number_format($session->price_per_slot, 2) }}</div>
                        </div>
                    </div>

                </div>
                <div class="card-footer text-center bg-white border-top-0">
                    <a href="{{ route('sessions') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left-circle"></i> Back to Sessions
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Bootstrap Icons CDN --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endsection
