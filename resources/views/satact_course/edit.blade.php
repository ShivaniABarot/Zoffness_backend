@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center rounded-top">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-pencil-square me-2"></i>Edit SAT/ACT Package
                    </h4>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('satact_course.update', $package->id) }}" method="POST">
                        @csrf
                        {{-- @method('PUT') --}} {{-- Uncomment if your route expects a PUT method --}}

                        {{-- Package Name --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">Package Name</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ old('name', $package->name) }}" required>
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Price --}}
                        <div class="mb-3">
                            <label for="price" class="form-label">Package Price</label>
                            <input type="number" step="0.01" name="price" id="price" class="form-control"
                                value="{{ old('price', $package->price) }}" required>
                            @error('price')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label for="description" class="form-label">Package Description</label>
                            <textarea name="description" id="description" class="form-control" rows="4"
                                placeholder="Enter description...">{{ old('description', $package->description) }}</textarea>
                            @error('description')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('satact_course.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save2-fill"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Bootstrap Icons CDN --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endsection
