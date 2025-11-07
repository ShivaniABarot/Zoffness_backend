@extends('layouts.app')
@section('title', 'Program Details')

@section('content')
<div class="card">
    <!-- <div class="card-header">
        <h5>{{ $program->title }}</h5>
    </div> -->
    <div class="card-body">
        @if($program->icon_image)
            <div class="mb-3 text-center">
                <img src="{{ asset('storage/' . $program->icon_image) }}" width="150" class="rounded shadow">
            </div>
        @endif

        <!-- Dynamic description paragraph -->
        <!-- <p class="text-muted">{{ $program->short_description }}</p> -->

        <!-- Dynamic card layout for features -->
        <div class="card p-3 mt-3">
            <h4 class="mb-3">{{ $program->title }}</h4> <!-- Use title dynamically -->
            <p class="text-muted">{{ $program->short_description }}</p> <!-- Reuse description -->

            <!-- Dynamically generate bullet points (assuming short_description has key points) -->
            <ul class="list-unstyled">
                @php
                    $points = explode("\n", $program->short_description); // Split by newline as an example
                    foreach ($points as $point) {
                        if (trim($point)) {
                            echo '<li class="mb-2"><i class="bi bi-check-circle text-primary"></i> ' . e(trim($point)) . '</li>';
                        }
                    }
                @endphp
            </ul>


            @if($program->link)
            <p class="mt-3"><strong>Link:</strong> <a href="{{ $program->link }}" target="_blank">{{ $program->link }}</a></p>
        @endif
        
            <!-- Dynamic action button (replace '#' with actual route) -->
            <!-- <a href="{{ $program->link ?: '#' }}" class="btn btn-primary btn-block mt-3" target="_blank">
                {{ $program->title }} Action
            </a>
            <a href="{{ $program->link ?: '#' }}" class="text-decoration-none text-muted mt-2 d-block text-center">Learn More</a> -->
        </div>

       

        <a href="{{ route('programs.index') }}" class="btn btn-secondary mt-3">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>
</div>
@endsection