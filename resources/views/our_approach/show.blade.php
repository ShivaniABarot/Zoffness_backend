@extends('layouts.app')
@section('title', 'View Our Approach')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-eye"></i> Our Approach Details</h2>
      
    </div>

    <div class="card shadow-sm p-4">

        {{-- Section Title --}}
        <div class="mb-3">
            <h5 class="text-primary">Section Title:</h5>
            <p class="fs-5 fw-semibold mb-0">{{ $ourApproach->section_title }}</p>
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <h5 class="text-primary">Description:</h5>
            @if($ourApproach->description)
                <p>{{ $ourApproach->description }}</p>
            @else
                <p>-</p>
            @endif
        </div>

        {{-- Image --}}
        <div class="mb-3">
            <h5 class="text-primary">Image:</h5>
            @if($ourApproach->image)
                <img src="{{ asset('storage/' . $ourApproach->image) }}" 
                     alt="Approach Image" 
                     width="300" 
                     class="rounded shadow-sm mb-2">
            @else
                <p>-</p>
            @endif
        </div>

        {{-- Highlights --}}
        <div class="mb-3">
            <h5 class="text-primary">Highlights:</h5>
            @if($ourApproach->highlights)
                @php
                    $highlights = json_decode($ourApproach->highlights, true);
                @endphp
                @if(is_array($highlights) && count($highlights))
                    <ul>
                        @foreach($highlights as $highlight)
                            <li>{{ $highlight }}</li>
                        @endforeach
                    </ul>
                @else
                    <p>-</p>
                @endif
            @else
                <p>-</p>
            @endif
        </div>

    

    </div>
</div>
@endsection
