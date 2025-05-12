@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Package</h1>
        <form action="{{ route('collage_essays_packages.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" name="price" class="form-control" value="{{ old('price') }}" required>
            </div>
            <!-- <div class="mb-3">
                <label for="number_of_sessions" class="form-label">Number of Sessions</label>
                <input type="number" name="number_of_sessions" class="form-control" value="{{ old('number_of_sessions') }}"
                    required>
            </div> -->
            <div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea name="description" class="form-control" required>{{ old('description') }}</textarea>
</div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
