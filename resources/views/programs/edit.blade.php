@extends('layouts.app')
@section('title', 'Edit Program')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Edit Program</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('programs.update', $program->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Program Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $program->title) }}" required>
                @error('title') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Short Description</label>
                <textarea name="short_description" class="form-control" rows="4" required>{{ old('short_description', $program->short_description) }}</textarea>
                @error('short_description') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Icon / Image</label><br>
                @if($program->icon_image)
                    <img src="{{ asset('storage/'.$program->icon_image) }}" width="100" class="mb-2 rounded">
                @endif
                <input type="file" name="icon_image" class="form-control">
                @error('icon_image') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Program Link</label>
                <input type="url" name="link" class="form-control" value="{{ old('link', $program->link) }}">
                @error('link') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save2"></i> Update Program
            </button>
            <a href="{{ route('programs.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
