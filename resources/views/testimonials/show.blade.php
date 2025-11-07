@extends('layouts.app')
@section('title', 'View Testimonial')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-eye"></i> Testimonial Details</h2>
        <a href="{{ route('testimonials.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left-circle"></i> Back
        </a>
    </div>

    <div class="card shadow-sm p-4">

        {{-- Name --}}
        <div class="mb-3">
            <h5 class="text-primary">Name:</h5>
            <p>{{ $testimonial->name }}</p>
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <h5 class="text-primary">Email:</h5>
            <p>{{ $testimonial->email ?? 'No email provided' }}</p>
        </div>

        {{-- Relationship --}}
        <div class="mb-3">
            <h5 class="text-primary">Relationship:</h5>
            <p>{{ $testimonial->relationship ?? 'N/A' }}</p>
        </div>

        {{-- Rating --}}
        <div class="mb-3">
            <h5 class="text-primary">Rating:</h5>
            <p>{{ $testimonial->rating ?? 'N/A' }}/5</p>
        </div>

        {{-- Testimonial --}}
        <div class="mb-3">
            <h5 class="text-primary">Testimonial:</h5>
            <p>{{ $testimonial->testimonial }}</p>
        </div>

        {{-- Consent --}}
        <div class="mb-3">
            <h5 class="text-primary">Consent:</h5>
            <p>{{ $testimonial->consent ? 'Yes' : 'No' }}</p>
        </div>

        {{-- Buttons --}}
        <div class="d-flex gap-2 mt-3">
            <a href="{{ route('testimonials.edit', $testimonial->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil-square"></i> Edit
            </a>
            <a href="{{ route('testimonials.index') }}" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Back
            </a>
        </div>

    </div>
</div>
@endsection
