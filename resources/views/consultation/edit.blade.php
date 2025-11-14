@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4><i class="bx bx-edit"></i> Edit Consultation</h4>

    <div class="card p-4 shadow-sm mt-3">
        <form method="POST" action="{{ route('consultation.update', $consultation->id) }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Consultation Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $consultation->name }}" required>
            </div>

            <div class="mb-3">
                <label for="amount" class="form-label">Amount ($)</label>
                <input type="number" name="amount" id="amount" class="form-control" step="0.01" value="{{ $consultation->amount }}" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description (optional)</label>
                <textarea name="description" id="description" class="form-control" rows="3">{{ $consultation->description }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">
                <i class="bx bx-save"></i> Update
            </button>
            <a href="{{ route('consultation.index') }}" class="btn btn-secondary ms-2">Cancel</a>
        </form>
    </div>
</div>
@endsection
