@extends('layouts.app')
@section('title', 'Edit Our Approach')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-pencil-square"></i> Edit Our Approach</h2>
    </div>

    <div class="card shadow-sm p-4">
        <form action="{{ route('our-approach.update', $approach->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Section Title --}}
            <div class="mb-3">
                <label for="section_title" class="form-label">Section Title <span class="text-danger">*</span></label>
                <input type="text" name="section_title" id="section_title" class="form-control" 
                       value="{{ old('section_title', $approach->section_title) }}" required>
                @error('section_title') 
                    <div class="text-danger small">{{ $message }}</div> 
                @enderror
            </div>

            {{-- Description --}}
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="5" class="form-control">{{ old('description', $approach->description) }}</textarea>
                @error('description') 
                    <div class="text-danger small">{{ $message }}</div> 
                @enderror
            </div>

            {{-- Image --}}
            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" name="image" id="image" class="form-control mb-2">
                <small class="text-muted d-block mb-2">Allowed types: jpg, jpeg, png, webp | Max: 2MB</small>

                @if($approach->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $approach->image) }}" 
                             alt="Current Image" 
                             class="img-thumbnail" 
                             style="max-height: 150px;">
                    </div>
                    <small class="text-muted">Current Image</small>
                @endif

                @error('image') 
                    <div class="text-danger small">{{ $message }}</div> 
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Update
                </button>
                <a href="{{ route('our-approach.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
