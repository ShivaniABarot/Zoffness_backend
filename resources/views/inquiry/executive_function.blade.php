@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="mb-2 text-center">
        <h2 class="fw-bold mb-1">Executive Function Coaching</h2>
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
                        <th>Package</th>
                        <th>Total Amount</th>
                        {{-- <th>Actions</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse($coaching as $coaching)
                        <tr class="text-center">
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-capitalize">{{ $coaching->parent_first_name }} {{ $coaching->parent_last_name }}</td>
                            <td>
                                <a href="tel:{{ $coaching->parent_phone }}" class="text-decoration-none text-primary">{{ $coaching->parent_phone }}</a>
                            </td>
                            <td>
                                <a href="mailto:{{ $coaching->parent_email }}" class="text-decoration-none text-primary">{{ $coaching->parent_email }}</a>
                            </td>
                            <td class="text-capitalize">{{ $coaching->student_first_name }} {{ $coaching->student_last_name }}</td>
                            <td>
                                @if($coaching->student_email)
                                    <a href="mailto:{{ $coaching->student_email }}" class="text-decoration-none text-primary">{{ $coaching->student_email }}</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill">
                                    {{ $coaching->package_type }}
                                </span>
                            </td>
                            <td class="fw-semibold">${{ number_format($coaching->subtotal, 2) }}</td>
                            <td>
                                {{-- Optional actions (uncomment if needed) --}}
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
