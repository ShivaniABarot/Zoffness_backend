@extends('layouts.app')
@section('title', 'Add SAT/ACT Page Section')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-plus-circle"></i> Add SAT/ACT Page Section</h2>
    </div>

    <div class="card shadow-sm p-4">
    <form action="{{ route('master_sat_act_page.store') }}" method="POST" enctype="multipart/form-data">

            @csrf

            {{-- Section Type --}}
            <!-- <div class="mb-3">
                <label for="section_type" class="form-label">Section Type <span class="twext-danger">*</span></label>
                <input type="text" name="section_type" id="section_type" class="form-control" 
                       value="{{ old('section_type') }}" placeholder="e.g. hero, about, benefits" required>
                @error('section_type') 
                    <div class="text-danger small">{{ $message }}</div> 
                @enderror
            </div> -->

            {{-- Title --}}
            <div class="mb-3">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" class="form-control" 
                       value="{{ old('title') }}" required>
                @error('title') 
                    <div class="text-danger small">{{ $message }}</div> 
                @enderror
            </div>

            {{-- Subtitle --}}
            <div class="mb-3">
                <label for="subtitle" class="form-label">Subtitle</label>
                <input type="text" name="subtitle" id="subtitle" class="form-control" 
                       value="{{ old('subtitle') }}">
                @error('subtitle') 
                    <div class="text-danger small">{{ $message }}</div> 
                @enderror
            </div>

            {{-- Description --}}
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="5" class="form-control">{{ old('description') }}</textarea>
                @error('description') 
                    <div class="text-danger small">{{ $message }}</div> 
                @enderror
            </div>

            {{-- Icon --}}
            <div class="mb-3">
                <label for="icon" class="form-label">Icon (Bootstrap Icon class)</label>
                <input type="text" name="icon" id="icon" class="form-control" 
                       value="{{ old('icon') }}" placeholder="e.g. bi bi-book">
                @error('icon') 
                    <div class="text-danger small">{{ $message }}</div> 
                @enderror
            </div>

            {{-- Image --}}
            <div class="mb-3">
                <label for="image_path" class="form-label">Image</label>
                <input type="file" name="image_path" id="image_path" class="form-control">
                <small class="text-muted">Allowed types: jpg, jpeg, png, webp | Max: 2MB</small>
                @error('image_path') 
                    <div class="text-danger small">{{ $message }}</div> 
                @enderror
            </div>

            {{-- Point Text --}}
            <div class="mb-3">
                <label for="point_text" class="form-label">Point Text (comma-separated)</label>
                <input type="text" name="point_text" id="point_text" class="form-control" 
                       value="{{ old('point_text') }}" placeholder="e.g. Personalized learning, Expert tutors">
                @error('point_text') 
                    <div class="text-danger small">{{ $message }}</div> 
                @enderror
            </div>

            {{-- Button Text --}}
            <div class="mb-3">
                <label for="button_text" class="form-label">Button Text</label>
                <input type="text" name="button_text" id="button_text" class="form-control" 
                       value="{{ old('button_text') }}" placeholder="e.g. Learn More">
                @error('button_text') 
                    <div class="text-danger small">{{ $message }}</div> 
                @enderror
            </div>

            {{-- Button Link --}}
            <div class="mb-3">
                <label for="button_link" class="form-label">Button Link</label>
                <input type="url" name="button_link" id="button_link" class="form-control" 
                       value="{{ old('button_link') }}" placeholder="https://example.com">
                @error('button_link') 
                    <div class="text-danger small">{{ $message }}</div> 
                @enderror
            </div>

            {{-- Order Index --}}
            <div class="mb-3">
                <label for="order_index" class="form-label">Order Index</label>
                <input type="number" name="order_index" id="order_index" class="form-control" 
                       value="{{ old('order_index', 0) }}">
                @error('order_index') 
                    <div class="text-danger small">{{ $message }}</div> 
                @enderror
            </div>

            {{-- Status --}}
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status', 1) == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status') 
                    <div class="text-danger small">{{ $message }}</div> 
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save"></i> Save
                </button>
                <a href="{{ route('master_sat_act_page.index') }}" class="btn btn-secondary">

                    <i class="bi bi-x-circle"></i> Cancel
                </a>
            </div>

        </form>
    </div>
</div>
@endsection
