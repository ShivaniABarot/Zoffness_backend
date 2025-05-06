
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Booking</h1>
        <form action="{{ route('bookings.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="session_id" class="form-label">Session</label>
                <select name="session_id" class="form-control" required>
                    @foreach ($sessions as $session)
                        <option value="{{ $session->id }}">{{ $session->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="timeslot_id" class="form-label">Timeslot</label>
                <select name="timeslot_id" class="form-control" required>
                    @foreach ($timeslots as $timeslot)
                        <option value="{{ $timeslot->id }}">{{ $timeslot->start_time }} - {{ $timeslot->end_time }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="package_id" class="form-label">Package (Optional)</label>
                <select name="package_id" class="form-control">
                    @foreach ($packages as $package)
                        <option value="{{ $package->id }}">{{ $package->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>
@endsection
