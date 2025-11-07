@extends('layouts.app')
@section('title', 'Add Program')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Add New Program</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('programs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Program Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                @error('title') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Short Description</label>
                <textarea name="short_description" class="form-control" rows="4" required>{{ old('short_description') }}</textarea>
                @error('short_description') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Icon / Image</label>
                <input type="file" name="icon_image" class="form-control">
                @error('icon_image') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Program Link</label>
                <input type="url" name="link" class="form-control" value="{{ old('link') }}">
                @error('link') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn btn-success">
                <i class="bi bi-save"></i> Save Program
            </button>
            <a href="{{ route('programs.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
