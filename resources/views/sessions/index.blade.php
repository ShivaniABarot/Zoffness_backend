@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Session List</h5>
            <a href="{{ route('sessions.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Add Session
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
                <table id="sessionsTable" class="table table-striped table-hover datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Price Per Slot</th>
                            <th>Max Capacity</th>
                            <th class="text-center no-export">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $session)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $session->title }}</td>
                                <td>
                                    <span class="badge bg-label-{{ $session->session_type == 'regular' ? 'primary' : 'info' }}">
                                        {{ ucfirst($session->session_type) }}
                                    </span>
                                </td>
                                <td class="fw-semibold">${{ number_format($session->price_per_slot, 2) }}</td>
                                <td>{{ $session->max_capacity }}</td>
                                <td class="text-center">
                                    <div class="d-inline-flex">
                                        <a href="{{ route('sessions.show', $session->id) }}" class="btn btn-sm btn-icon btn-action-view" title="View">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <a href="{{ route('sessions.edit', $session->id) }}" class="btn btn-sm btn-icon btn-action-edit" title="Edit">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-icon btn-action-delete"
                                                onclick="deleteSession({{ $session->id }})" title="Delete">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="No Data" width="80" class="mb-3 opacity-50">
                                        <p class="mb-0">No sessions found.</p>
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
    function deleteSession(sessionId) {
        // Show SweetAlert confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to delete this session?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, send AJAX request to delete the session
                $.ajax({
                    url: '{{ route('sessions.delete', '') }}/' + sessionId,
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
                    error: function(xhr) {
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
        initDataTable('sessionsTable', {
            // Any custom options specific to this table
            order: [[1, 'asc']], // Sort by title column
            columnDefs: [
                { width: "5%", targets: [0] },       // Make # column narrow
                { width: "35%", targets: [1] },      // Title column width
                { width: "15%", targets: [2] },      // Type column width
                { width: "15%", targets: [3] },      // Price column width
                { width: "15%", targets: [4] },      // Capacity column width
                { width: "15%", targets: [5] },      // Actions column width
                { className: 'fw-semibold', targets: [3] }, // Make price column bold
                { className: 'text-center', targets: [2] }, // Center the type badges
                { orderable: false, targets: [5] }   // Disable sorting on actions column
            ]
        });
    });
</script>
@endpush

@endsection
