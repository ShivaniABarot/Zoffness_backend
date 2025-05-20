@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 hover-card animate__animated animate__fadeIn">
                <!-- Header Section -->
                <div class="card-header bg-gradient-primary text-white text-center position-relative">
                    <div class="avatar avatar-xl mx-auto mb-3">
                        @if($user->profile_picture)
                            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->username }}" class="rounded-circle">
                        @else
                            <span class="avatar-initial rounded-circle bg-primary text-white">
                                {{ strtoupper(substr($user->username, 0, 2)) }}
                            </span>
                        @endif
                    </div>
                    <h3 class="mb-0 fw-bold">{{ $user->username }}'s Profile</h3>
                    <p class="text-light mt-2 opacity-75">Manage your account details</p>
                </div>
                <!-- Body Section -->
                <div class="card-body py-4">
                    <!-- User Details -->
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center hover-item">
                            <span class="fw-semibold text-dark"><i class="bx bx-user me-2"></i> Username</span>
                            <span class="text-muted">{{ $user->username }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center hover-item">
                            <span class="fw-semibold text-dark"><i class="bx bx-envelope me-2"></i> Email Address</span>
                            <span class="text-muted">{{ $user->email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center hover-item">
                            <span class="fw-semibold text-dark"><i class="bx bx-id-card me-2"></i> Role</span>
                            <span class="badge bg-label-primary rounded-pill">{{ ucfirst($user->role) }}</span>
                        </li>
                    </ul>
                </div>
                <!-- Footer Section -->
                <div class="card-footer text-center bg-light">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm rounded-pill px-4 me-2">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet" />
<style>
    /* Card Styling */
    .card {
        border-radius: 1.25rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: linear-gradient(145deg, #ffffff, #f8f9fa);
        overflow: hidden;
    }

    .hover-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
    }

    .card-header.bg-gradient-primary {
        background: linear-gradient(135deg, #007bff, #00aaff);
        border-radius: 1.25rem 1.25rem 0 0;
        padding: 2.5rem;
        position: relative;
        overflow: hidden;
    }

    .card-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(255,255,255,0.25), transparent);
        opacity: 0.3;
    }

    .card-header h3 {
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 1.8rem;
        color: #ffffff;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .card-header p {
        font-size: 1rem;
        font-weight: 400;
        color: #e6f0ff;
    }

    .card-body {
        padding: 2.5rem;
    }

    .list-group-item {
        border: none;
        padding: 1.5rem;
        background: transparent;
        transition: all 0.3s ease;
        border-radius: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .hover-item:hover {
        background: #e6f0ff;
        transform: translateX(10px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    .fw-semibold {
        font-weight: 600;
        color: #1a1a1a;
        display: flex;
        align-items: center;
    }

    .text-muted {
        font-size: 1rem;
        color: #6c757d;
    }

    .badge.bg-label-primary {
        background: #e6f0ff;
        color: #007bff;
        font-weight: 500;
        padding: 0.6rem 1.2rem;
        font-size: 0.9rem;
    }

    .btn-primary {
        background: #007bff;
        border: none;
        border-radius: 1rem;
        padding: 0.8rem 2rem;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .btn-primary:hover {
        background: #0056b3;
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    /* Avatar Styling */
    .avatar.avatar-xl {
        width: 90px;
        height: 90px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s ease;
    }

    .avatar.avatar-xl img, .avatar.avatar-xl .avatar-initial {
        width: 100%;
        height: 100%;
        object-fit: cover;
        font-size: 2.5rem;
        font-weight: 600;
        background: #0056b3;
        border: 4px solid #ffffff;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .avatar.avatar-xl:hover {
        transform: scale(1.1);
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .card-header {
            padding: 2rem;
        }

        .card-header h3 {
            font-size: 1.6rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .list-group-item {
            padding: 1rem;
        }

        .btn-primary {
            padding: 0.7rem 1.8rem;
            font-size: 0.9rem;
        }

        .avatar.avatar-xl {
            width: 70px;
            height: 70px;
        }

        .avatar.avatar-xl .avatar-initial {
            font-size: 2rem;
        }
    }

    @media (max-width: 576px) {
        .card-header {
            padding: 1.5rem;
        }

        .card-header h3 {
            font-size: 1.3rem;
        }

        .card-body {
            padding: 1rem;
        }

        .list-group-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.75rem;
        }

        .avatar.avatar-xl {
            width: 60px;
            height: 60px;
        }

        .avatar.avatar-xl .avatar-initial {
            font-size: 1.5rem;
        }

        .btn-primary {
            width: 100%;
            margin-bottom: 0.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new WOW().init();
    });
</script>
@endpush