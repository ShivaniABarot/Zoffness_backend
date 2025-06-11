@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Create Announcement</h3>

    <form action="{{ route('announcements.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" id="title" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea id="message" name="message" class="form-control" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label for="publish_at" class="form-label">Publish At (Optional)</label>
            <input type="datetime-local" id="publish_at" name="publish_at" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('announcements.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
