@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center rounded-top">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-people-fill me-2"></i>{{ $student->student_name }}'s Details
                    </h4>
                </div>
                <div class="card-body px-4 py-4">
                    {{-- Parent Name --}}
                    <div class="mb-4 d-flex align-items-center">
                        <i class="bi bi-person-lines-fill text-primary me-2 fs-5"></i>
                        <div>
                            <strong>Parent Name:</strong>
                            <div class="text-muted">{{ $student->parent_name }}</div>
                        </div>
                    </div>

                    {{-- Parent Phone --}}
                    <div class="mb-4 d-flex align-items-center">
                        <i class="bi bi-telephone-fill text-primary me-2 fs-5"></i>
                        <div>
                            <strong>Parent Phone:</strong>
                            <div class="text-muted">{{ $student->parent_phone }}</div>
                        </div>
                    </div>

                    {{-- Parent Email --}}
                    <div class="mb-4 d-flex align-items-center">
                        <i class="bi bi-envelope-fill text-primary me-2 fs-5"></i>
                        <div>
                            <strong>Parent Email:</strong>
                            <div class="text-muted">{{ $student->parent_email }}</div>
                        </div>
                    </div>

                    {{-- Student Email --}}
                    <div class="mb-4 d-flex align-items-center">
                        <i class="bi bi-envelope-paper-fill text-primary me-2 fs-5"></i>
                        <div>
                            <strong>Student Email:</strong>
                            <div class="text-muted">{{ $student->student_email }}</div>
                        </div>
                    </div>

                    {{-- School --}}
                    <div class="mb-4 d-flex align-items-center">
                        <i class="bi bi-building text-primary me-2 fs-5"></i>
                        <div>
                            <strong>School:</strong>
                            <div class="text-muted">{{ $student->school }}</div>
                        </div>
                    </div>

                    {{-- Bank Name --}}
                    <div class="mb-4 d-flex align-items-center">
                        <i class="bi bi-bank text-primary me-2 fs-5"></i>
                        <div>
                            <strong>Bank Name:</strong>
                            <div class="text-muted">{{ $student->bank_name }}</div>
                        </div>
                    </div>

                    {{-- Account Number --}}
                    <div class="mb-4 d-flex align-items-center">
                        <i class="bi bi-credit-card-2-front-fill text-primary me-2 fs-5"></i>
                        <div>
                            <strong>Account Number:</strong>
                            <div class="text-muted">{{ $student->account_number }}</div>
                        </div>
                    </div>

                    {{-- Back Button --}}
                    <div class="mt-4 text-center">
                        <a href="{{ route('student') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left-circle"></i> Back to Students
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Bootstrap Icons CDN --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endsection
