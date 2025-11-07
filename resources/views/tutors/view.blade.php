@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center rounded-top">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-person-badge-fill me-2"></i>{{ $tutor->name }}'s Profile
                    </h4>
                </div>
                <div class="card-body px-4 py-4">

                    {{-- Tutor Name --}}
                    <div class="mb-4 d-flex align-items-center">
                        <i class="bi bi-person-fill text-primary me-2 fs-5"></i>
                        <div>
                            <strong>Name:</strong>
                            <div class="text-muted">{{ $tutor->name }}</div>
                        </div>
                    </div>

                    {{-- Designation --}}
                    <div class="mb-4 d-flex align-items-center">
                        <i class="bi bi-award-fill text-primary me-2 fs-5"></i>
                        <div>
                            <strong>Designation:</strong>
                            <div class="text-muted">{{ $tutor->designation }}</div>
                        </div>
                    </div>

                    {{-- Bio --}}
                    <div class="mb-4 d-flex align-items-start">
                        <i class="bi bi-card-text text-primary me-2 fs-5 mt-1"></i>
                        <div>
                            <strong>Bio:</strong>
                            <div class="text-muted">{{ $tutor->bio }}</div>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="mb-4 d-flex align-items-center">
                        <i class="bi bi-toggle-on text-primary me-2 fs-5"></i>
                        <div>
                            <strong>Status:</strong>
                            <div>
                                @if($tutor->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Profile Image --}}
                    <div class="mb-4 d-flex align-items-center">
                        <i class="bi bi-image text-primary me-2 fs-5"></i>
                        <div>
                            <strong>Profile Image:</strong>
                            <div class="mt-2">
                                @if ($tutor->image)
                                    <img src="{{ asset('storage/' . $tutor->image) }}" 
                                         alt="Tutor Image" 
                                         class="img-thumbnail shadow-sm rounded" 
                                         style="max-width: 150px;">
                                @else
                                    <span class="text-muted fst-italic">No image uploaded</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Back Button --}}
                    <div class="mt-4 text-center">
                        <a href="{{ route('tutors') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left-circle"></i> Back to Tutors
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
