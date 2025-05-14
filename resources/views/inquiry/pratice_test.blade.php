@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="mb-2 text-center">
        <h2 class="fw-bold mb-1">Pratice Test & Analysis List</h2>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Table Card --}}
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-borderless table-hover align-middle mb-0">
                <thead class="bg-light text-secondary fw-semibold text-uppercase small text-center">
                    <tr>
                        <th>#</th>
                        <th>Parent Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Student Name</th>
                        <th>Student Email</th>
                        <th>School</th>
                        <th>Total Amount</th>
                        <th>Packages</th>
                        <th>Exam Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($praticetests as $praticetest)
                        <tr class="text-center">
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-capitalize">{{ $praticetest->parent_first_name }} {{ $praticetest->parent_last_name }}</td>
                            <td>
                                <a href="tel:{{ $praticetest->parent_phone }}" class="text-decoration-none text-primary">{{ $praticetest->parent_phone }}</a>
                            </td>
                            <td>
                                <a href="mailto:{{ $praticetest->parent_email }}" class="text-decoration-none text-primary">{{ $praticetest->parent_email }}</a>
                            </td>
                            <td class="text-capitalize">{{ $praticetest->student_first_name }} {{ $praticetest->student_last_name }}</td>
                            <td>
                                @if($praticetest->student_email)
                                    <a href="mailto:{{ $praticetest->student_email }}" class="text-decoration-none text-primary">{{ $praticetest->student_email }}</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $praticetest->school }}</td>
                            <td class="fw-semibold">${{ number_format($praticetest->subtotal, 2) }}</td>
                            <td>
                                @php
                                    $dates = json_decode($praticetest->date, true) ?? [];
                                @endphp
                                @forelse ($dates as $index => $date)
                                    @php
                                        $dateWithoutSuffix = preg_replace('/(\d+)(st|nd|rd|th)/', '$1', $date);
                                        $dateWithoutExtraText = preg_replace('/ @.*$/', '', $dateWithoutSuffix);
                                        try {
                                            $formattedDate = \Carbon\Carbon::parse($dateWithoutExtraText)->format('d F, l @gA');
                                        } catch (\Exception $e) {
                                            $formattedDate = 'Invalid Date';
                                        }
                                    @endphp
                                    {{ $index + 1 }}. {{ $formattedDate }}<br>
                                @empty
                                    N/A
                                @endforelse
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
                            <td colspan="10" class="text-center py-5">
                                <div class="text-muted">
                                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="No Data" width="80" class="mb-3 opacity-50">
                                    <p class="mb-0">No enrollments found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
