@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Add Timeslot</h1>
    
    <form action="{{ route('timeslots.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="session_id">Session</label>
            <select name="session_id" id="session_id" class="form-control" required>
                <option value="">Select Session</option>
                @foreach($sessions as $session)
                    <option value="{{ $session->id }}">{{ $session->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="room">Room</label>
            <input type="text" name="room" id="room" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" name="date" id="date" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="start_time">Start Time</label>
            <input type="time" name="start_time" id="start_time" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="end_time">End Time</label>
            <input type="time" name="end_time" id="end_time" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="available_seats">Available Seats</label>
            <input type="number" name="available_seats" id="available_seats" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Submit</button>
    </form>
</div>
@endsection
