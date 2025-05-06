@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Booking</h1>

        <form action="{{ route('bookings.update', $booking->id) }}" method="POST">
            @csrf
            {{-- @method('PUT') --}}

            <!-- Session Selection -->
            <div class="mb-3">
                <label for="session_id" class="form-label">Session</label>
                <select name="session_id" class="form-control" required>
                    @foreach ($sessions as $session)
                        <option value="{{ $session->id }}" {{ $session->id == $booking->session_id ? 'selected' : '' }}>
                            {{ $session->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Timeslot Selection -->
            <div class="mb-3">
                <label for="timeslot_id" class="form-label">Timeslot</label>
                <select name="timeslot_id" class="form-control" required>
                    @foreach ($timeslots as $timeslot)
                        <option value="{{ $timeslot->id }}" {{ $timeslot->id == $booking->timeslot_id ? 'selected' : '' }}>
                            {{ $timeslot->start_time }} - {{ $timeslot->end_time }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Package Selection -->
            <div class="mb-3">
                <label for="package_id" class="form-label">Package (Optional)</label>
                <select name="package_id" class="form-control">
                    <option value="">No Package</option>
                    @foreach ($packages as $package)
                        <option value="{{ $package->id }}" {{ $package->id == $booking->package_id ? 'selected' : '' }}>
                            {{ $package->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Selection -->
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Update Booking</button>
            <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
