@extends('layouts.app')
@section('title', 'Edit Testimonial')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-pencil-square"></i> Edit Testimonial</h2>
        <a href="{{ route('testimonials.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left-circle"></i> Back
        </a>
    </div>

    <div class="card shadow-sm p-4">
        <form action="{{ route('testimonials.update', $testimonial->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div class="mb-3">
                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $testimonial->name) }}" class="form-control" required>
                @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $testimonial->email) }}" class="form-control">
                @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- Relationship --}}
            <div class="mb-3">
                <label for="relationship" class="form-label">Relationship</label>
                <input type="text" name="relationship" id="relationship" value="{{ old('relationship', $testimonial->relationship) }}" class="form-control">
                @error('relationship') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- Rating --}}
            <div class="mb-3">
                <label for="rating" class="form-label">Rating (1-5)</label>
                <input type="number" name="rating" id="rating" value="{{ old('rating', $testimonial->rating) }}" class="form-control" min="1" max="5">
                @error('rating') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- Testimonial --}}
            <div class="mb-3">
                <label for="testimonial" class="form-label">Testimonial <span class="text-danger">*</span></label>
                <textarea name="testimonial" id="testimonial" rows="5" class="form-control" required>{{ old('testimonial', $testimonial->testimonial) }}</textarea>
                @error('testimonial') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- Consent --}}
            <div class="mb-3 form-check">
                <input type="checkbox" name="consent" id="consent" class="form-check-input" {{ old('consent', $testimonial->consent) ? 'checked' : '' }}>
                <label for="consent" class="form-check-label">Consent to display</label>
                @error('consent') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- Buttons --}}
            <div class="d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save"></i> Update
                </button>
                <a href="{{ route('testimonials.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
