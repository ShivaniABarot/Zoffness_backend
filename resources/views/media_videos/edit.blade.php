{{-- resources/views/media_videos/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Media Video')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bx bx-edit"></i> Edit Media Video</h2>
        <a href="{{ route('media-videos.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Back to List
        </a>
    </div>

    <div class="card p-4 shadow-sm">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('media-videos.update', $video->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $video->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="order" class="form-label">Order</label>
                        <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $video->order) }}" min="0">
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

        {{-- Replace ONLY this section in edit.blade.php --}}
<div class="mb-3">
    <label for="video" class="form-label">Upload New Video (Optional)</label>
    <input type="file" class="form-control @error('video') is-invalid @enderror" id="video" name="video" accept="video/*">
    <small class="form-text text-muted">Current: <strong>{{ basename($video->video_url) }}</strong> | Supported: MP4, MOV, AVI, WMV, FLV (Max 100MB)</small>
    @error('video')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Keep the current video preview --}}
<div class="mb-3">
    <label class="form-label">Current Video Preview</label>
    <video width="100%" height="200" controls class="border rounded">
        <source src="{{ asset('storage' . $video->video_url) }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $video->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input @error('is_active') is-invalid @enderror" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $video->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Active
                    </label>
                </div>
                @error('is_active')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('media-videos.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Video</button>
            </div>
        </form>
    </div>
</div>
@endsection