@extends('layouts.app')

@push('styles')
    @include('partials.datatables_scripts')
@endpush

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="mb-2 text-center">
        <h2 class="fw-bold mb-1">Enrollment List</h2>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-body">
            <table id="enrollmentTable" class="table table-striped table-bordered display responsive nowrap" style="width:100%">
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
                    </tr>
                </thead>
                <tbody>
                    @forelse($enrollments as $enrollment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-capitalize">{{ $enrollment->parent_first_name . ' ' . $enrollment->parent_last_name }}</td>
                            <td>
                                <a href="tel:{{ $enrollment->parent_phone }}" class="text-decoration-none text-primary">{{ $enrollment->parent_phone }}</a>
                            </td>
                            <td>
                                <a href="mailto:{{ $enrollment->parent_email }}" class="text-decoration-none text-primary">{{ $enrollment->parent_email }}</a>
                            </td>
                            <td class="text-capitalize">{{ $enrollment->student_first_name . ' ' . $enrollment->student_last_name }}</td>
                            <td>
                                @if($enrollment->student_email)
                                    <a href="mailto:{{ $enrollment->student_email }}" class="text-decoration-none text-primary">{{ $enrollment->student_email }}</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $enrollment->school }}</td>
                            <td class="fw-semibold">${{ number_format($enrollment->total_amount, 2) }}</td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill">
                                    {{ $enrollment->packages }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
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

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable with custom options
        initDataTable('enrollmentTable', {
            // Any custom options specific to this table
            order: [[0, 'asc']],
            columnDefs: [
                { className: 'fw-semibold', targets: [7] }, // Make amount column bold
                { className: 'text-center', targets: [8] }  // Center the package badges
            ]
        });
    });
</script>
@endpush
@endsection
