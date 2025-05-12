@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center">SAT/ACT Course List</h1>
        
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
                    <th>Exam Date & Time</th>
                    <th>Packages</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sat_act_course as $course)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $course->parent_firstname . ' ' . $course->parent_lastname }}</td>
                        <td><a href="tel:{{ $course->parent_phone }}" class="text-decoration-none">{{ $course->parent_phone }}</a></td>
                        <td><a href="mailto:{{ $course->parent_email }}" class="text-decoration-none">{{ $course->parent_email }}</a></td>
                        <td>{{ $course->student_firstname . ' ' . $course->student_lastname }}</td>
                        <td>
                            @if($course->student_email)
                                <a href="mailto:{{ $course->student_email }}" class="text-decoration-none">{{ $course->student_email }}</a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $course->school }}</td>
                        <td>${{ number_format($course->amount, 2) }}</td>
                        <td>
    @php
        try {
            $formattedDate = \Carbon\Carbon::parse($course->created_at)->format('d F, l @gA');
        } catch (\Exception $e) {
            $formattedDate = 'Invalid Date';
        }
    @endphp
    {{ $formattedDate }}
</td>

<td>{{ $course->package_name }}</td>      
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">No SAT/ACT courses found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        @can('create', App\Models\SAT_ACT_Course::class)
            <a href="{{ route('sat_act_course.create') }}" class="btn btn-primary mt-3">Add New Course</a>
        @endcan
    </div>
@endsection