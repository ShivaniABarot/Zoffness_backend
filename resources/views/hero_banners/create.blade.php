@extends('layouts.app')
@section('title', 'Create Hero Banner')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Create Hero Banner</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('hero-banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="tagline" class="form-label">Tagline <span class="text-danger">*</span></label>
                <input type="text" name="tagline" id="tagline" class="form-control @error('tagline') is-invalid @enderror" value="{{ old('tagline') }}" required>
                @error('tagline') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
        <label for="background_image" class="form-label">Background Image <span class="text-danger">*</span></label>
        <input type="file" name="background_image" id="background_image" class="form-control" required>
    </div>

            <div class="mb-3">
                <label for="cta_text" class="form-label">CTA Text</label>
                <input type="text" name="cta_text" id="cta_text" class="form-control" value="{{ old('cta_text') }}">
            </div>

            <div class="mb-3">
                <label for="cta_link" class="form-label">CTA Link</label>
                <input type="url" name="cta_link" id="cta_link" class="form-control" value="{{ old('cta_link') }}">
            </div>

            <button type="submit" class="btn btn-success">Save Banner</button>
            <a href="{{ route('hero-banners.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
