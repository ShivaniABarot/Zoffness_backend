@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3>{{ $tutor->name }}'s Profile</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <h5 class="font-weight-bold">Designation</h5>
                        </div>
                        <div class="col-md-8">
                            <p>{{ $tutor->designation }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <h5 class="font-weight-bold">Designation</h5>
                        </div>
                        <div class="col-md-8">
                            <p>{{ $tutor->designation }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <h5 class="font-weight-bold">Bio</h5>
                        </div>
                        <div class="col-md-8">
                            <p>{{ $tutor->bio }}</p>
                        </div>
                    </div>

                    <!-- Back Button -->
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('tutors') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left-circle"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Bootstrap Icons (Optional) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
@endsection
