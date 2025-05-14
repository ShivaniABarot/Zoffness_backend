@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="mb-2 text-center">
        <h2 class="fw-bold mb-1">College Admission Counseling</h2>
       
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
                        <th scope="col">#</th>
                        <th scope="col">Parent Name</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Email</th>
                        <th scope="col">Student Name</th>
                        <th scope="col">Student Email</th>
                        <th scope="col">School</th>
                        <th scope="col">Package</th>
                        <th scope="col">Amount</th>
                        {{-- <th scope="col">Actions</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse($collegeadmission as $collegeadmission)
                        <tr class="text-center">
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-capitalize">{{ $collegeadmission->parent_first_name }} {{ $collegeadmission->parent_last_name }}</td>
                            <td>
                                <a href="tel:{{ $collegeadmission->parent_phone }}" class="text-decoration-none text-primary">{{ $collegeadmission->parent_phone }}</a>
                            </td>
                            <td>
                                <a href="mailto:{{ $collegeadmission->parent_email }}" class="text-decoration-none text-primary">{{ $collegeadmission->parent_email }}</a>
                            </td>
                            <td class="text-capitalize">{{ $collegeadmission->student_first_name }} {{ $collegeadmission->student_last_name }}</td>
                            <td>
                                @if($collegeadmission->student_email)
                                    <a href="mailto:{{ $collegeadmission->student_email }}" class="text-decoration-none text-primary">{{ $collegeadmission->student_email }}</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $collegeadmission->school }}</td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill">
                                    {{ $collegeadmission->packages }}
                                </span>
                            </td>
                            <td class="fw-semibold">${{ number_format($collegeadmission->subtotal, 2) }}</td>
                            <td>
                                {{-- Optional Actions --}}
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
