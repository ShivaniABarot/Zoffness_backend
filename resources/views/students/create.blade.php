<!-- resources/views/students/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add New Student</h1>
    <form action="{{ route('students.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="parent_name" class="form-label">Parent Name</label>
            <input type="text" name="parent_name" id="parent_name" class="form-control @error('parent_name') is-invalid @enderror" value="{{ old('parent_name') }}">
            @error('parent_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="parent_phone" class="form-label">Parent Phone</label>
            <input type="text" name="parent_phone" id="parent_phone" class="form-control @error('parent_phone') is-invalid @enderror" value="{{ old('parent_phone') }}">
            @error('parent_phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="parent_email" class="form-label">Parent Email</label>
            <input type="email" name="parent_email" id="parent_email" class="form-control @error('parent_email') is-invalid @enderror" value="{{ old('parent_email') }}">
            @error('parent_email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="student_name" class="form-label">Student Name</label>
            <input type="text" name="student_name" id="student_name" class="form-control @error('student_name') is-invalid @enderror" value="{{ old('student_name') }}">
            @error('student_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="student_email" class="form-label">Student Email</label>
            <input type="email" name="student_email" id="student_email" class="form-control @error('student_email') is-invalid @enderror" value="{{ old('student_email') }}">
            @error('student_email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="school" class="form-label">School</label>
            <input type="text" name="school" id="school" class="form-control @error('school') is-invalid @enderror" value="{{ old('school') }}">
            @error('school')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="bank_name" class="form-label">Bank Name</label>
            <input type="text" name="bank_name" id="bank_name" class="form-control @error('bank_name') is-invalid @enderror" value="{{ old('bank_name') }}">
            @error('bank_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="account_number" class="form-label">Account Number</label>
            <input type="text" name="account_number" id="account_number" class="form-control @error('account_number') is-invalid @enderror" value="{{ old('account_number') }}">
            @error('account_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
