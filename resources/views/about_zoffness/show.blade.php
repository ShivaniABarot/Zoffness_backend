@extends('layouts.app')
@section('title', 'View About Zoffness')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bx bx-show"></i> About Zoffness Details</h2>
        <a href="{{ route('about-zoffness.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Back
        </a>
    </div>

    <div class="card shadow-sm p-4">

        {{-- Title --}}
        <div class="mb-3">
            <h5 class="text-primary">Title:</h5>
            <p>{{ $about_zoffness->title }}</p>
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <h5 class="text-primary">Description:</h5>
            <p>{{ $about_zoffness->description }}</p>
        </div>

        {{-- Images --}}
        @foreach(['image_main' => 'Main Image', 'image_1' => 'Image 1', 'image_2' => 'Image 2'] as $field => $label)
            <div class="mb-3">
                <h5 class="text-primary">{{ $label }}:</h5>
                @if($about_zoffness->$field)
                    <img src="{{ asset('storage/' . $about_zoffness->$field) }}" alt="{{ $label }}" width="300" class="rounded shadow-sm mb-2">
                @else
                    <p>-</p>
                @endif
            </div>
        @endforeach

        {{-- CTA --}}
        <div class="mb-3">
            <h5 class="text-primary">CTA Text:</h5>
            <p>{{ $about_zoffness->cta_text ?? '—' }}</p>
        </div>

        <div class="mb-3">
            <h5 class="text-primary">CTA Link:</h5>
            @if($about_zoffness->cta_link)
                <a href="{{ $about_zoffness->cta_link }}" target="_blank">{{ $about_zoffness->cta_link }}</a>
            @else
                <p>—</p>
            @endif
        </div>

    </div>
</div>
@endsection
