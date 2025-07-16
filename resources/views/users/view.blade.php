@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center rounded-top">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-person-circle me-2"></i>User Details
                    </h4>
                </div>
                <div class="card-body px-4 py-4">

                    <div class="mb-4 d-flex align-items-center">
                        <i class="bi bi-person-fill text-primary me-2 fs-5"></i>
                        <div>
                            <strong>Full Name:</strong>
                            <div class="text-muted">{{ $user->firstname }} {{ $user->lastname }}</div>
                        </div>
                    </div>

                    <div class="mb-4 d-flex align-items-center">
                        <i class="bi bi-envelope-fill text-primary me-2 fs-5"></i>
                        <div>
                            <strong>Email:</strong>
                            <div class="text-muted">{{ $user->email }}</div>
                        </div>
                    </div>

                    <div class="mb-4 d-flex align-items-center">
                        <i class="bi bi-telephone-fill text-primary me-2 fs-5"></i>
                        <div>
                            <strong>Phone Number:</strong>
                            <div class="text-muted">{{ $user->phone_no }}</div>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('users') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left-circle"></i> Back to Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Icons (if not already included globally) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endsection
