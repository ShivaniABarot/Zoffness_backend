@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center rounded-top">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-pencil-square me-2"></i>Edit Package
                    </h4>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('collage_essays_packages.update', $CollageEssaysPackage->id) }}" method="POST">
                        @csrf

                        {{-- Package Name --}}
                        <div class="form-floating mb-3">
                            <input type="text" name="name" id="name" class="form-control" placeholder="Package Name"
                                value="{{ old('name', $CollageEssaysPackage->name) }}" required>
                            <label for="name"><i class="bi bi-card-text me-2"></i>Package Name</label>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Package Price --}}
                        <div class="form-floating mb-3">
                            <input type="number" step="0.01" name="price" id="price" class="form-control" placeholder="Package Price"
                                value="{{ old('price', $CollageEssaysPackage->price) }}" required>
                            <label for="price"><i class="bi bi-currency-dollar me-2"></i>Package Price</label>
                            @error('price')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Package Description --}}
                        <div class="form-floating mb-3">
                            <textarea name="description" id="description" class="form-control" placeholder="Enter description..." style="height: 100px" required>{{ old('description', $CollageEssaysPackage->description) }}</textarea>
                            <label for="description"><i class="bi bi-info-circle-fill me-2"></i>Description</label>
                            @error('description')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('collage_essays_packages.index') }}" class="btn btn-outline-secondary">
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

{{-- Bootstrap Icons --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endsection
