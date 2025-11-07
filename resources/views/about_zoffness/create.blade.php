@extends('layouts.app')
@section('title', 'Add About Zoffness')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-plus-circle"></i> Add About Zoffness</h2>
        <a href="{{ route('about-zoffness.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left-circle"></i> Back
        </a>
    </div>

    <div class="card shadow-sm p-4">
        <form action="{{ route('about-zoffness.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Title --}}
            <div class="mb-3">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" class="form-control" required value="{{ old('title') }}">
                @error('title') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- Description --}}
            <div class="mb-3">
                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                <textarea name="description" id="description" rows="4" class="form-control" required>{{ old('description') }}</textarea>
                @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- Main Image --}}
            <div class="mb-3">
                <label for="image_main" class="form-label">Main Image</label>
                <input type="file" name="image_main" id="image_main" class="form-control" accept="image/*">
                @error('image_main') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- Image 1 --}}
            <div class="mb-3">
                <label for="image_1" class="form-label">Image 1</label>
                <input type="file" name="image_1" id="image_1" class="form-control" accept="image/*">
                @error('image_1') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- Image 2 --}}
            <div class="mb-3">
                <label for="image_2" class="form-label">Image 2</label>
                <input type="file" name="image_2" id="image_2" class="form-control" accept="image/*">
                @error('image_2') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- CTA Text --}}
            <div class="mb-3">
                <label for="cta_text" class="form-label">CTA Text</label>
                <input type="text" name="cta_text" id="cta_text" class="form-control" value="{{ old('cta_text') }}">
                @error('cta_text') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- CTA Link --}}
            <div class="mb-3">
                <label for="cta_link" class="form-label">CTA Link</label>
                <input type="url" name="cta_link" id="cta_link" class="form-control" value="{{ old('cta_link') }}">
                @error('cta_link') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            {{-- Buttons --}}
            <div class="d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save"></i> Save
                </button>
                <a href="{{ route('about-zoffness.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
