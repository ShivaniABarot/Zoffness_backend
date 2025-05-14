@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Executive Function Coaching</h1>
    {{-- @can('create', App\Models\PraticeTest::class)
        <a href="{{ route('enroll.create') }}" class="btn btn-primary mb-3">New Enrollment</a>
    @endcan --}}
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
                <th>Package</th>
                <th>Total Amount</th>
                {{-- <th>Actions</th> --}}
            </tr>
        </thead>
        <tbody>
            @forelse($coaching as $coaching)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $coaching->parent_first_name . ' ' . $coaching->parent_last_name }}</td>
                    <td><a href="tel:{{ $coaching->parent_phone }}" class="text-decoration-none">{{ $coaching->parent_phone }}</a></td>
                    <td><a href="mailto:{{ $coaching->parent_email }}" class="text-decoration-none">{{ $coaching->parent_email }}</a></td>
                    <td>{{ $coaching->student_first_name . ' ' . $coaching->student_last_name }}</td>
                    <td>
                        @if($coaching->student_email)
                            <a href="mailto:{{ $coaching->student_email }}" class="text-decoration-none">{{ $coaching->student_email }}</a>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $coaching->package_type }}</td>                    
                    <td>${{ number_format($coaching->subtotal, 2) }}</td>
                    
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
