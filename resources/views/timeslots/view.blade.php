@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Timeslot Details</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Session Name:</strong>
                        <p class="text-muted mb-0">{{ $session->title }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Room:</strong>
                        <p class="text-muted mb-0">{{ $timeslot->room }}</p>
                    </div>
                    <div>
                        <strong>Date:</strong>
                        <p class="text-muted mb-0">{{ $timeslot->date }}</p>
                    </div>
                    <div>
                        <strong>Start Time:</strong>
                        <p class="text-muted mb-0">{{ $timeslot->start_time }}</p>
                    </div>
                    <div>
                        <strong>End Time:</strong>
                        <p class="text-muted mb-0">{{ $timeslot->end_time }}</p>
                    </div>
                </div>
            </div>
            <div class="mt-4 text-center">
                <a href="{{ route('timeslots.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Timeslot
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
