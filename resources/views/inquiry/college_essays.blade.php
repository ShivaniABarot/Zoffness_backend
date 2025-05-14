@extends('layouts.app')

@push('styles')
    @include('partials.datatables_scripts')
@endpush

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="mb-2 text-center">
        <h2 class="fw-bold mb-1">College Essays</h2>
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
            <table id="collegeEssaysTable" class="table table-striped table-bordered display responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Parent Name</th>
                        <th>Parent Phone</th>
                        <th>Parent Email</th>
                        <th>Student Name</th>
                        <th>Student Email</th>
                        <th>Package</th>
                        <th>Total Sessions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($collegessays as $essay)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-capitalize">{{ $essay->parent_first_name . ' ' . $essay->parent_last_name }}</td>
                            <td>
                                <a href="tel:{{ $essay->parent_phone }}" class="text-decoration-none text-primary">{{ $essay->parent_phone }}</a>
                            </td>
                            <td>
                                <a href="mailto:{{ $essay->parent_email }}" class="text-decoration-none text-primary">{{ $essay->parent_email }}</a>
                            </td>
                            <td class="text-capitalize">{{ $essay->student_first_name . ' ' . $essay->student_last_name }}</td>
                            <td>
                                @if($essay->student_email)
                                    <a href="mailto:{{ $essay->student_email }}" class="text-decoration-none text-primary">{{ $essay->student_email }}</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill">
                                    {{ $essay->packages }}
                                </span>
                            </td>
                            <td class="fw-semibold">${{ number_format($essay->sessions, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="No Data" width="80" class="mb-3 opacity-50">
                                    <p class="mb-0">No college essays records found.</p>
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
        initDataTable('collegeEssaysTable', {
            // Any custom options specific to this table
            order: [[0, 'asc']],
            columnDefs: [
                { className: 'fw-semibold', targets: [7] }, // Make amount column bold
                { className: 'text-center', targets: [6] }  // Center the package badges
            ]
        });
    });
</script>
@endpush
@endsection
