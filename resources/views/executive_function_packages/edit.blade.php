@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Edit Executive addmissions Package</h1>

    <form action="{{ route('executive_function_packages.update', $ExecutivePackage->id) }}" method="POST">
    @csrf

        <div class="form-group">
            <label for="name">Package Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $ExecutivePackage->name) }}" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="price">Package Price</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" value="{{ old('price', $ExecutivePackage->price) }}" required>
            @error('price')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Package Description</label>
            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $ExecutivePackage->description) }}</textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
