@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Tutors</h5>
                <a href="{{ route('tutors.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Add New Tutor
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
                    <table id="tutorsTable" class="table table-striped table-hover datatable align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Image</th> <!-- ✅ Added -->
                                <th>Name</th>
                                <th>Designation</th>
                                <th>Bio</th>
                                <th>Status</th>
                                <th class="text-center no-export">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tutors as $tutor)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <!-- Tutor Image -->
                                    <td>
                                        @if($tutor->image)
                                        <img src="{{ asset('storage/' . $tutor->image) }}"

                                                 alt="{{ $tutor->name }}"
                                                 width="60"
                                                 height="60"
                                                 class="rounded-circle border">
                                        @else
                                            <img src="{{ asset('images/default-avatar.png') }}"
                                                 alt="No Image"
                                                 width="60"
                                                 height="60"
                                                 class="rounded-circle border opacity-75">
                                        @endif
                                    </td>

                                    <td>{{ $tutor->name }}</td>
                                    <td>{{ $tutor->designation }}</td>
                                    <td>{{ Str::limit($tutor->bio, 80) }}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input status-toggle" type="checkbox"
                                                data-id="{{ $tutor->id }}" {{ $tutor->status === 'active' ? 'checked' : '' }}>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <div class="d-inline-flex">
                                            <a href="{{ route('tutors.show', $tutor->id) }}"
                                               class="btn btn-sm btn-icon btn-action-view text-primary" title="View">
                                                <i class="bx bx-show"></i>
                                            </a>
                                            <a href="{{ route('tutors.edit', $tutor->id) }}"
                                               class="btn btn-sm btn-icon btn-action-edit text-warning" title="Edit">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-icon btn-action-delete text-danger"
                                                    onclick="deleteTutor({{ $tutor->id }})" title="Delete">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="No Data"
                                                 width="80" class="mb-3 opacity-50">
                                            <p class="mb-0">No tutors found.</p>
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

    {{-- Delete Function --}}
    <script>
        function deleteTutor(tutorId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to delete this tutor profile?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('tutors.delete', '') }}/' + tutorId,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire('Deleted!', response.message, 'success').then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error!', response.message, 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error!', 'An unexpected error occurred.', 'error');
                        }
                    });
                }
            });
        }
    </script>

    @push('scripts')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

        <script>
            $(document).ready(function () {
                // ✅ Initialize DataTable
                initDataTable('tutorsTable', {
                    order: [[2, 'asc']],
                    columnDefs: [
                        { width: "5%", targets: [0] },
                        { width: "10%", targets: [1] }, // Image
                        { width: "15%", targets: [2] }, // Name
                        { width: "15%", targets: [3] }, // Designation
                        { width: "25%", targets: [4] }, // Bio
                        { width: "10%", targets: [5] }, // Status
                        { orderable: false, targets: [6] } // Actions
                    ]
                });

                // ✅ Status toggle confirmation
                $('.status-toggle').on('change', function () {
                    const tutorId = $(this).data('id');
                    const isActive = $(this).is(':checked');
                    const newStatus = isActive ? 'active' : 'inactive';
                    const actionText = isActive ? 'activate' : 'deactivate';

                    Swal.fire({
                        title: 'Are you sure?',
                        text: `Do you want to ${actionText} this tutor?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: `Yes, ${actionText} it!`,
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            updateTutorStatus(tutorId, newStatus);
                        } else {
                            $(this).prop('checked', !isActive); // Revert toggle
                        }
                    });
                });

                function updateTutorStatus(tutorId, status) {
                    $.ajax({
                        url: `/tutors/status/${tutorId}`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            status: status
                        },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire('Success!', response.message, 'success');
                            } else {
                                Swal.fire('Error!', response.message, 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
