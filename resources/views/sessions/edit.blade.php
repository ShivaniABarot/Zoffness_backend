@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Session</h1>

        <form action="{{ route('sessions.update', $session->id) }}" method="POST">
            @csrf
            {{-- @method('PUT') --}}

            <div class="mb-3">
                <label for="title" class="form-label">Session Title</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $session->title }}" required>
            </div>

            <div class="mb-3">
                <label for="session_type" class="form-label">Session Type</label>
                <select class="form-control" id="session_type" name="session_type" required>
                    <option value="study" {{ $session->session_type == 'study' ? 'selected' : '' }}>Study</option>
                    <option value="exam" {{ $session->session_type == 'exam' ? 'selected' : '' }}>Exam</option>
                    <option value="extended_exam" {{ $session->session_type == 'extended_exam' ? 'selected' : '' }}>Extended
                        Exam</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="price_per_slot" class="form-label">Price Per Slot</label>
                <input type="number" class="form-control" id="price_per_slot" name="price_per_slot" step="0.01"
                    value="{{ $session->price_per_slot }}" required>
            </div>
            <div class="mb-3">
                <label for="max_capacity" class="form-label">Max Capacity</label>
                <input type="number" class="form-control" id="max_capacity" name="max_capacity"
                    value="{{ $session->max_capacity }}" required>
            </div>

            <button type="submit" class="btn btn-warning">Submit</button>
        </form>
    </div>
@endsection
