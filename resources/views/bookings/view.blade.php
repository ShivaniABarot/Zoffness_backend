@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-center">Booking Details</h1>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Booking #{{ $booking->id }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>Session:</strong>
                            </div>
                            <div class="col-6 text-end">
                                {{ $booking->session->title }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>Timeslot:</strong>
                            </div>
                            <div class="col-6 text-end">
                                {{ $booking->timeslot->start_time }} - {{ $booking->timeslot->end_time }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>Package:</strong>
                            </div>
                            <div class="col-6 text-end">
                                {{ $booking->package->name ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>Status:</strong>
                            </div>
                            <div class="col-6 text-end">
                                <span class="badge bg-{{ $booking->status == 'confirmed' ? 'success' : ($booking->status == 'cancelled' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>Remaining Sessions:</strong>
                            </div>
                            <div class="col-6 text-end">
                                {{ $booking->remaining_sessions ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('bookings.index') }}" class="btn btn-primary">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
