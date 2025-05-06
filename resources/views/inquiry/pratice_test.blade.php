@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Pratice Test & Analysis List</h1>
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
                <th>School</th>
                <th>Total Amount</th>
                <th>Packages</th>
                <th>Exam Date & Time</th>
                {{-- <th>Actions</th> --}}
            </tr>
        </thead>
        <tbody>
            @forelse($praticetest as $praticetest)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $praticetest->parent_first_name . ' ' . $praticetest->parent_last_name }}</td>
                    <td><a href="tel:{{ $praticetest->parent_phone }}" class="text-decoration-none">{{ $praticetest->parent_phone }}</a></td>
                    <td><a href="mailto:{{ $praticetest->parent_email }}" class="text-decoration-none">{{ $praticetest->parent_email }}</a></td>
                    <td>{{ $praticetest->student_first_name . ' ' . $praticetest->student_last_name }}</td>
                    <td>
                        @if($praticetest->student_email)
                            <a href="mailto:{{ $praticetest->student_email }}" class="text-decoration-none">{{ $praticetest->student_email }}</a>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $praticetest->school }}</td>
                    <td>${{ number_format($praticetest->subtotal, 2) }}</td>
                    {{-- <td>{{ $praticetest->test_type }}</td> --}}
                    <td>
                        @foreach (json_decode($praticetest->test_type) as $index => $type)
                            {{ $index + 1 }}. {{ $type }}<br>
                        @endforeach
                    </td>
                    
                    {{-- <td>{{ $praticetest->date }}</td> --}}
                    <td>
                        @foreach (json_decode($praticetest->date) as $key => $dates)
                            @foreach ($dates as $index => $date)
                                @php
                                    // Remove ordinal suffixes (st, nd, rd, th) and extra text
                                    $dateWithoutSuffix = preg_replace('/(\d+)(st|nd|rd|th)/', '$1', $date);
                                    
                                    // Remove everything after '@' (we only want the date and time)
                                    $dateWithoutExtraText = preg_replace('/ @.*$/', '', $dateWithoutSuffix);
                    
                                    // Convert the cleaned date to Carbon instance
                                    $formattedDate = \Carbon\Carbon::parse($dateWithoutExtraText)->format('d F, l @gA');
                                @endphp
                                {{ $index + 1 }}. {{ $formattedDate }}<br>
                            @endforeach
                        @endforeach
                    </td>
                    
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
