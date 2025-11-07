@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center rounded-top">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-box-seam-fill me-2"></i>Executive Admissions Package Details
                    </h4>
                </div>

                <div class="card-body px-4 py-4">

                    {{-- Package Name --}}
                    <div class="mb-4 d-flex align-items-center">
                        <i class="bi bi-bookmark-fill text-primary me-2 fs-5"></i>
                        <div>
                            <strong>Package Name:</strong>
                            <div class="text-muted">{{ $ExecutivePackage->name ?? 'N/A' }}</div>
                        </div>
                    </div>

                    {{-- Price --}}
                    <div class="mb-4 d-flex align-items-center">
                        <i class="bi bi-currency-dollar text-primary me-2 fs-5"></i>
                        <div>
                            <strong>Package Price:</strong>
                            <div class="text-muted">
                                {{ isset($ExecutivePackage->price) ? '$' . number_format($ExecutivePackage->price, 2) : 'N/A' }}
                            </div>
                        </div>
                    </div>

           

                    {{-- Status --}}
                    <div class="mb-4 d-flex align-items-center">
                        <i class="bi bi-toggle-on text-primary me-2 fs-5"></i>
                        <div>
                            <strong>Status:</strong>
                            <div class="text-muted">{{ ucfirst($ExecutivePackage->status ?? 'N/A') }}</div>
                        </div>
                    </div>

                </div>

                <div class="card-footer text-center bg-white border-top-0">
                    <a href="{{ route('executive_function_packages.index') }}" class="btn btn-outline-secondary">
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
