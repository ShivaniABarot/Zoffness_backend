@extends('layouts.app')
@section('title', 'Edit Hero Banner')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Edit Hero Banner</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('hero-banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="tagline" class="form-label">Tagline <span class="text-danger">*</span></label>
                <input type="text" name="tagline" id="tagline" class="form-control @error('tagline') is-invalid @enderror" value="{{ old('tagline', $banner->tagline) }}" required>
                @error('tagline') 
                    <div class="invalid-feedback">{{ $message }}</div> 
                @enderror
            </div>

            <div class="mb-3">
                <label for="background_image" class="form-label">Background Image</label>
                <input type="file" name="background_image" id="background_image" class="form-control @error('background_image') is-invalid @enderror">
                @if($banner->background_image)
                    <img src="{{ asset('storage/' . $banner->background_image) }}" class="mt-2" width="150">
                @endif
                @error('background_image') 
                    <div class="invalid-feedback">{{ $message }}</div> 
                @enderror
            </div>

            <div class="mb-3">
                <label for="cta_text" class="form-label">CTA Text</label>
                <input type="text" name="cta_text" id="cta_text" class="form-control @error('cta_text') is-invalid @enderror" value="{{ old('cta_text', $banner->cta_text) }}">
                @error('cta_text') 
                    <div class="invalid-feedback">{{ $message }}</div> 
                @enderror
            </div>

            <div class="mb-3">
                <label for="cta_link" class="form-label">CTA Link</label>
                <input type="url" name="cta_link" id="cta_link" class="form-control @error('cta_link') is-invalid @enderror" value="{{ old('cta_link', $banner->cta_link) }}">
                @error('cta_link') 
                    <div class="invalid-feedback">{{ $message }}</div> 
                @enderror
            </div>

            <button type="submit" class="btn btn-success">Update Banner</button>
            <a href="{{ route('hero-banners.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
