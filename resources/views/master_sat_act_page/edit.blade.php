@extends('layouts.app')
@section('title', 'Edit SAT/ACT Page Section')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-pencil-square"></i> Edit SAT/ACT Page Section</h2>
    </div>

    <div class="card shadow-sm p-4">
        <form action="{{ route('master_sat_act_page.update', $record->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Section Type --}}
            <!-- <div class="mb-3">
                <label for="section_type" class="form-label">Section Type <span class="text-danger">*</span></label>
                <input type="text" name="section_type" id="section_type" class="form-control" 
                       value="{{ old('section_type', $record->section_type) }}" required>
                @error('section_type') 
                    <div class="text-danger small">{{ $message }}</div> 
                @enderror
            </div> -->

            {{-- Title --}}
            <div class="mb-3">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" class="form-control" 
                       value="{{ old('title', $record->title) }}" required>
                @error('title') 
                    <div class="text-danger small">{{ $message }}</div> 
                @enderror
            </div>

            {{-- Subtitle --}}
            <div class="mb-3">
                <label for="subtitle" class="form-label">Subtitle</label>
                <input type="text" name="subtitle" id="subtitle" class="form-control" 
                       value="{{ old('subtitle', $record->subtitle) }}">
                @error('subtitle') 
                    <div class="text-danger small">{{ $message }}</div> 
                @enderror
            </div>

            {{-- Description --}}
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="5" class="form-control">{{ old('description', $record->description) }}</textarea>
                @error('description') 
                    <div class="text-danger small">{{ $message }}</div> 
                @enderror
            </div>

            {{-- Icon --}}
            <div class="mb-3">
                <label for="icon" class="form-label">Icon (Bootstrap Icon class)</label>
                <input type="text" name="icon" id="icon" class="form-control" 
                       value="{{ old('icon', $record->icon) }}" placeholder="e.g. bi bi-book">
                @error('icon') 
                    <div class="text-danger small">{{ $message }}</div> 
                @enderror
            </div>

            {{-- Current Image --}}
            @if($record->image_path)
                <div class="mb-3">
                    <label class="form-label">Current Image</label><br>
                    <img src="{{ asset('storage/' . $record->image_path) }}" width="120" height="120" class="rounded mb-2">
                </div>
            @endif

            {{-- Image Upload --}}
            <div class="mb-3">
                <label for="image_path" class="form-label">Change Image</label>
                <input type="file" name="image_path" id="image_path" class="form-control">
                <small class="text-muted">Allowed: jpg, jpeg, png, webp | Max: 2MB</small>
                @error('image_path') 
                    <div class="text-danger small">{{ $message }}</div> 
                @enderror
            </div>

            {{-- Point Text --}}
            <div class="mb-3">
                <label for="point_text" class="form-label">Point Text (comma-separated)</label>
                <input type="text" name="point_text" id="point_text" class="form-control" 
                       value="{{ old('point_text', $record->point_text) }}">
                @error('point_text') 
                    <div class="text-danger small">{{ $message }}</div> 
                @enderror
            </div>

            {{-- Button Text --}}
            <div class="mb-3">
                <label for="button_text" class="form-label">Button Text</label>
                <input type="text" name="button_text" id="button_text" class="form-control" 
                       value="{{ old('button_text', $record->button_text) }}">
                @error('button_text') 
                    <div class="text-danger small">{{ $message }}</div> 
                @enderror
            </div>

            {{-- Button Link --}}
            <div class="mb-3">
                <label for="button_link" class="form-label">Button Link</label>
                <input type="url" name="button_link" id="button_link" class="form-control" 
                       value="{{ old('button_link', $record->button_link) }}">
                @error('button_link') 
                    <div class="text-danger small">{{ $message }}</div> 
                @enderror
            </div>

            {{-- Order Index --}}
            <div class="mb-3">
                <label for="order_index" class="form-label">Order Index</label>
                <input type="number" name="order_index" id="order_index" class="form-control" 
                       value="{{ old('order_index', $record->order_index) }}">
                @error('order_index') 
                    <div class="text-danger small">{{ $message }}</div> 
                @enderror
            </div>

            {{-- Status --}}
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="1" {{ old('status', $record->status) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status', $record->status) == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status') 
                    <div class="text-danger small">{{ $message }}</div> 
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save"></i> Update
                </button>
                <a href="{{ route('master_sat_act_page.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
            </div>

        </form>
    </div>
</div>
@endsection
