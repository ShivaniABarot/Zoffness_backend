@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center rounded-top">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-box-seam-fill me-2"></i>Package Details
                    </h4>
                </div>

                <div class="card-body px-4 py-4">

                    {{-- Package Name --}}
                    <div class="mb-4 d-flex align-items-center">
                        <i class="bi bi-bookmark-fill text-primary me-2 fs-5"></i>
                        <div>
                            <strong>Package Name:</strong>
                            <div class="text-muted">{{ $SAT_ACT_Packages->name ?? 'N/A' }}</div>
                        </div>
                    </div>

                    {{-- Price --}}
                    <div class="mb-4 d-flex align-items-center">
                        <i class="bi bi-currency-dollar text-primary me-2 fs-5"></i>
                        <div>
                            <strong>Package Price:</strong>
                            <div class="text-muted">
                                {{ isset($SAT_ACT_Packages->price) ? '$' . number_format($SAT_ACT_Packages->price, 2) : 'N/A' }}
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="mb-4 d-flex align-items-start">
                        <i class="bi bi-card-text text-primary me-2 fs-5 mt-1"></i>
                        <div>
                            <strong>Package Description:</strong>
                            <div class="text-muted">{{ $SAT_ACT_Packages->description ?? 'N/A' }}</div>
                        </div>
                    </div>

                </div>

                <div class="card-footer text-center bg-white border-top-0">
                    <a href="{{ route('satact_course.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left-circle"></i> Back to Packages
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Bootstrap Icons CDN --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endsection
