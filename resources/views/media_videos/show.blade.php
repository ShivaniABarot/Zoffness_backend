{{-- resources/views/media_videos/show.blade.php --}}
@extends('layouts.app')

@section('title', 'View Media Video')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bx bx-eye"></i> View Media Video</h2>
        <div>
            <a href="{{ route('media-videos.edit', $mediaVideo->id) }}" class="btn btn-primary me-2">
                <i class="bx bx-edit"></i> Edit
            </a>
            <a href="{{ route('media-videos.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Back to List
            </a>
        </div>
    </div>

    <div class="card p-4 shadow-sm">
        <div class="row">
            <div class="col-md-6">
                <h5 class="card-title">Title</h5>
                <p class="card-text">{{ $mediaVideo->title }}</p>
            </div>
            <div class="col-md-6">
                <h5 class="card-title">Order</h5>
                <p class="card-text">{{ $mediaVideo->order }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h5 class="card-title">Video URL</h5>
                <p class="card-text">
                    <a href="{{ $mediaVideo->video_url }}" target="_blank" class="text-decoration-none">
                        <i class="bx bx-link-external"></i> {{ $mediaVideo->video_url }}
                    </a>
                </p>
                @if(str_contains($mediaVideo->video_url, '.mp4'))
                    <div class="mt-3">
                        <h6>Video Preview:</h6>
                        <video width="100%" height="200" controls>
                            <source src="{{ asset('storage' . $mediaVideo->video_url) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h5 class="card-title">Description</h5>
                <p class="card-text">{{ $mediaVideo->description ?? 'No description provided.' }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h5 class="card-title">Status</h5>
                <span class="badge {{ $mediaVideo->is_active ? 'bg-success' : 'bg-danger' }}">
                    {{ $mediaVideo->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>

    
    </div>
</div>
@endsection