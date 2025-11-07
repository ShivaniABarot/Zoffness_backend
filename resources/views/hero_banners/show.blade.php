@extends('layouts.app')
@section('title', 'View Hero Banner')

@section('content')
<div class="card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bx bx-show"></i> Banner Details</h2>
        <a href="{{ route('hero-banners.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Back
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-6">
                    <h5><strong>Tagline:</strong></h5>
                    <p class="text-muted">{{ $heroBanner->tagline }}</p>
                </div>
                <div class="col-md-6">
                    <h5><strong>CTA Text:</strong></h5>
                    <p class="text-muted">{{ $heroBanner->cta_text ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6">
                    <h5><strong>CTA Link:</strong></h5>
                    <p class="text-muted">
                        @if($heroBanner->cta_link)
                            <a href="{{ $heroBanner->cta_link }}" target="_blank">{{ $heroBanner->cta_link }}</a>
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                <div class="col-md-6">
                    <h5><strong>Background Image:</strong></h5>
                    @if($heroBanner->background_image)
                        <img src="{{ asset('storage/' . $heroBanner->background_image) }}" 
                             class="img-fluid rounded shadow-sm" style="max-width: 400px; max-height: 200px;">
                    @else
                        <p class="text-muted">No image</p>
                    @endif
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('hero-banners.edit', $heroBanner) }}" class="btn btn-warning">
                    <i class="bx bx-edit"></i> Edit
                </a>
                <a href="{{ route('hero-banners.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>
</div>
@endsection