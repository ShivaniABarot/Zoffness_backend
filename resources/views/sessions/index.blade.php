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
                            <th>Date</th>
                            <th>Type</th>
                            <th>Price Per Slot</th>
                            <th>Max Capacity</th>
                            <th>Status</th>
                            <th class="text-center no-export">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $session)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $session->title }}</td>
                                <td>{{ $session->date }}</td>
                                <td>
                                    <span class="badge bg-label-{{ $session->session_type == 'regular' ? 'primary' : 'info' }}">
                                        {{ ucfirst($session->session_type) }}
                                    </span>
                                </td>
                                <td class="fw-semibold">${{ number_format($session->price_per_slot, 2) }}</td>
                                <td>{{ $session->max_capacity }}</td>
                                <td class="text-center">
                                    <label class="switch">
                                        <input type="checkbox" {{ $session->status == 'active' ? 'checked' : '' }}
                                            onchange="toggleStatus({{ $session->id }}, '{{ $session->status }}')">
                                        <span class="slider"></span>
                                    </label>
                                </td>
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
                                <td colspan="8" class="text-center py-5">
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

<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 26px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0;
        right: 0; bottom: 0;
        background-color: #e74c3c; /* red default */
        transition: .4s;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 20px; width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: #2ecc71; /* green when active */
    }

    input:checked + .slider:before {
        transform: translateX(24px);
    }
</style>

<script>
    function deleteSession(sessionId) {
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
                $.ajax({
                    url: '{{ route('sessions.delete', '') }}/' + sessionId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Deleted!', response.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'An unexpected error occurred.', 'error');
                    }
                });
            }
        });
    }

    function toggleStatus(sessionId, currentStatus) {
        const newStatus = currentStatus === 'active' ? 'in-active' : 'active';
        const confirmText = `Are you sure you want to ${newStatus.toLowerCase()} this session?`;

        Swal.fire({
            title: 'Change Status',
            text: confirmText,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: `Yes, ${newStatus}`,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/sessions/${sessionId}/toggle-status`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Updated!', response.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error!', 'Could not update status.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'An error occurred while updating status.', 'error');
                    }
                });
            } else {
                location.reload(); // Reset switch if canceled
            }
        });
    }
</script>

@push('scripts')
<script>
    $(document).ready(function() {
        initDataTable('sessionsTable', {
            order: [[1, 'asc']],
            columnDefs: [
                { width: "5%", targets: [0] },
                { width: "25%", targets: [1] },
                { width: "15%", targets: [2] },
                { width: "10%", targets: [3] },
                { width: "10%", targets: [4] },
                { width: "10%", targets: [5] },
                { width: "10%", targets: [6] },
                { width: "15%", targets: [7] },
                { className: 'fw-semibold', targets: [4] },
                { className: 'text-center', targets: [3, 6, 7] },
                { orderable: false, targets: [7] }
            ]
        });
    });
</script>
@endpush

@endsection
