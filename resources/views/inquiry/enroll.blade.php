@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Enrollment List</h1>
    @can('create', App\Models\Enroll::class)
        <a href="{{ route('enroll.create') }}" class="btn btn-primary mb-3">New Enrollment</a>
    @endcan
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Parent Name</th>
                <th>Parent Phone</th>
                <th>Parent Email</th>
                <th>Student Name</th>
                <th>Student Email</th>
                <th>School</th>
                <th>Total Amount</th>
                <th>Packages</th>
                {{-- <th>Actions</th> --}}
            </tr>
        </thead>
        <tbody>
            @forelse($enrollments as $enrollment)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $enrollment->parent_first_name . ' ' . $enrollment->parent_last_name }}</td>
                    <td><a href="tel:{{ $enrollment->parent_phone }}" class="text-decoration-none">{{ $enrollment->parent_phone }}</a></td>
                    <td><a href="mailto:{{ $enrollment->parent_email }}" class="text-decoration-none">{{ $enrollment->parent_email }}</a></td>
                    <td>{{ $enrollment->student_first_name . ' ' . $enrollment->student_last_name }}</td>
                    <td>
                        @if($enrollment->student_email)
                            <a href="mailto:{{ $enrollment->student_email }}" class="text-decoration-none">{{ $enrollment->student_email }}</a>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $enrollment->school }}</td>
                    <td>${{ number_format($enrollment->total_amount, 2) }}</td>
                    <td>{{ $enrollment->packages }}</td>
                    <td>
                        {{-- Uncomment the actions as needed --}}
                        {{-- <a href="{{ route('enroll.show', $enrollment->id) }}" class="btn btn-info btn-sm" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('enroll.edit', $enrollment->id) }}" class="btn btn-warning btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="#" class="btn btn-danger btn-sm" title="Delete" onclick="deleteEnroll({{ $enrollment->id }})">
                            <i class="fas fa-trash"></i>
                        </a> --}}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">No enrollments found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
