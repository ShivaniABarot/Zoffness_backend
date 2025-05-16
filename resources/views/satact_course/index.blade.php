@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">SAT-ACT Course List</h5>
            <a href="{{ route('satact_course.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Add SAT/ACT Course
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table id="satActCoursesTable" class="table table-striped table-hover datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Package Name</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th class="text-center no-export">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($SAT_ACT_Packages as $package)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $package->name }}</td>
                                <td class="fw-semibold">${{ number_format($package->price, 2) }}</td>
                                <td>{{ Str::limit($package->description, 100) }}</td>
                                <td class="text-center">
                                    <div class="d-inline-flex">
                                        <a href="{{ route('satact_course.show', $package->id) }}" class="btn btn-sm btn-icon btn-action-view" title="View">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <a href="{{ route('satact_course.edit', $package->id) }}" class="btn btn-sm btn-icon btn-action-edit" title="Edit">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-icon btn-action-delete"
                                                onclick="deletePackage({{ $package->id }})" title="Delete">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
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
</div>

<script>
    function deletePackage(packageId) {
        // Show SweetAlert confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to delete this course?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, send AJAX request to delete the package
                $.ajax({
                    url: '{{ route('satact_course.delete', '') }}/' + packageId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Deleted!',
                                response.message,
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'An unexpected error occurred.',
                            'error'
                        );
                    }
                });
            }
        });
    }
</script>

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable with custom options
        initDataTable('satActCoursesTable', {
            // Any custom options specific to this table
            order: [[1, 'asc']], // Sort by package name column
            columnDefs: [
                { width: "5%", targets: [0] },       // Make # column narrow
                { width: "30%", targets: [1] },      // Package name column width
                { width: "15%", targets: [2] },      // Price column width
                { width: "35%", targets: [3] },      // Description column width
                { width: "15%", targets: [4] },      // Actions column width
                { className: 'fw-semibold', targets: [2] }, // Make price column bold
                { orderable: false, targets: [4] }   // Disable sorting on actions column
            ]
        });
    });
</script>
@endpush

@endsection
