@extends('layouts.app')
@section('title', 'Edit About Zoffness')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bx bx-edit"></i> Edit About Zoffness</h2>
        <a href="{{ route('about-zoffness.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Back
        </a>
    </div>

    <div class="card shadow-sm p-4">
        <form action="{{ route('about-zoffness.update', $about_zoffness->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Title --}}
            <div class="mb-3">
                <label for="title" class="form-label">Section Title</label>
                <input type="text" name="title" id="title" value="{{ $about_zoffness->title }}" class="form-control" required>
            </div>

            {{-- Description --}}
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="4" class="form-control" required>{{ $about_zoffness->description }}</textarea>
            </div>

            {{-- Images --}}
            @foreach(['image_main' => 'Main Image', 'image_1' => 'Image 1', 'image_2' => 'Image 2'] as $field => $label)
                <div class="mb-3">
                    <label class="form-label">{{ $label }}</label><br>
                    @if($about_zoffness->$field)
                        <img src="{{ asset('storage/' . $about_zoffness->$field) }}" alt="{{ $label }}" width="150" class="rounded mb-2">
                    @endif
                    <input type="file" name="{{ $field }}" class="form-control" accept="image/*">
                </div>
            @endforeach

            {{-- CTA --}}
            <div class="mb-3">
                <label for="cta_text" class="form-label">CTA Text</label>
                <input type="text" name="cta_text" id="cta_text" value="{{ $about_zoffness->cta_text }}" class="form-control">
            </div>

            <div class="mb-3">
                <label for="cta_link" class="form-label">CTA Link</label>
                <input type="url" name="cta_link" id="cta_link" value="{{ $about_zoffness->cta_link }}" class="form-control">
            </div>

            <button type="submit" class="btn btn-success">
                <i class="bx bx-save"></i> Update
            </button>
        </form>
    </div>
</div>
@endsection