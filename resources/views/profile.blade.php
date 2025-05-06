<!-- resources/views/profile.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <!-- Header Section -->
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0">User Profile</h3>
                </div>
                <!-- Body Section -->
                <div class="card-body">
                    {{-- <div class="text-center mb-4">
                        <h4 class="mb-1">{{ $user->username }}</h4>
                        <p class="text-muted">{{ $user->email }}</p>
                    </div> --}}

                    {{-- <hr> --}}

                    <!-- User Details -->
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><strong>Username:</strong></span>
                            <span>{{ $user->username }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><strong>Email Address:</strong></span>
                            <span>{{ $user->email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><strong>Role:</strong></span>
                            <span>{{ ucfirst($user->role) }}</span>
                        </li>
                    </ul>
                </div>
                <!-- Footer Section -->
                <div class="card-footer text-center bg-light">
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
