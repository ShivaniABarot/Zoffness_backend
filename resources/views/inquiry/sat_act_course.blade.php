@extends('layouts.app')

@push('styles')
    <!-- Additional styles specific to this page can be added here -->
@endpush

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="mb-4 text-center">
        <h2 class="fw-bold mb-2" style="color: #566a7f; letter-spacing: -0.5px;">SAT/ACT Course List</h2>
        <p class="text-muted">View and manage all SAT/ACT course registrations</p>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden" style="background: #fff; transition: all 0.3s ease;">
        <div class="card-header bg-transparent border-0 pt-4 pb-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0" style="color: #566a7f; font-weight: 600;">Course Registrations</h5>
                <div class="card-actions">
                    @can('create', App\Models\SAT_ACT_Course::class)
                        <a href="{{ route('sat_act_course.create') }}" class="btn btn-primary btn-sm">
                            <i class="bx bx-plus me-1"></i> Add New
                        </a>
                    @endcan
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <table id="satActCourseTable" class="table table-striped table-bordered display responsive nowrap datatable" style="width:100%">
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
                            <td class="text-capitalize">{{ $course->parent_firstname . ' ' . $course->parent_lastname }}</td>
                            <td>
                                <a href="tel:{{ $course->parent_phone }}" class="text-decoration-none text-primary">{{ $course->parent_phone }}</a>
                            </td>
                            <td>
                                <a href="mailto:{{ $course->parent_email }}" class="text-decoration-none text-primary">{{ $course->parent_email }}</a>
                            </td>
                            <td class="text-capitalize">{{ $course->student_firstname . ' ' . $course->student_lastname }}</td>
                            <td>
                                @if($course->student_email)
                                    <a href="mailto:{{ $course->student_email }}" class="text-decoration-none text-primary">{{ $course->student_email }}</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $course->school }}</td>
                            <td class="fw-semibold">${{ number_format($course->amount, 2) }}</td>
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
                            <td>
                                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill" style="font-size: 0.85rem; font-weight: 500;">
                                    {{ $course->package_name }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <div class="text-muted">
                                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="No Data" width="80" class="mb-3 opacity-50">
                                    <p class="mb-0">No SAT/ACT courses found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable with custom options
        initDataTable('satActCourseTable', {
            // Any custom options specific to this table
            order: [[0, 'asc']],
            columnDefs: [
                { className: 'fw-semibold', targets: [7] }, // Make amount column bold
                { className: 'text-nowrap', targets: [8] }, // Prevent date from wrapping
                { className: 'text-center', targets: [9] }  // Center the package badges
            ]
        });
    });
</script>
@endpush
@endsection