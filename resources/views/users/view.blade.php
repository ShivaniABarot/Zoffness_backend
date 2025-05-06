@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">User Details</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Username:</strong>
                        <p class="text-muted mb-0">{{ $user->name }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong>
                        <p class="text-muted mb-0">{{ $user->email }}</p>
                    </div>
                    <div>
                        <strong>Role:</strong>
                        <p class="text-muted mb-0">{{ $user->role }}</p>
                    </div>
                </div>
            </div>
            <div class="mt-4 text-center">
                <a href="{{ route('users') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Users
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
