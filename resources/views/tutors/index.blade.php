
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
                <table id="tutorsTable" class="table table-striped table-hover datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Designation</th>
                            <th>Bio</th>
                            <th class="text-center no-export">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tutors as $tutor)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $tutor->name }}</td>
                                <td>{{ $tutor->email }}</td>
                                <td>{{ $tutor->designation }}</td>
                                <td>{{ Str::limit($tutor->bio, 100) }}</td>
                                <td class="text-center">
                                    <div class="d-inline-flex">
                                        <a href="{{ route('tutors.show', $tutor->id) }}" class="btn btn-sm btn-icon btn-action-view" title="View">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <a href="{{ route('tutors.edit', $tutor->id) }}" class="btn btn-sm btn-icon btn-action-edit" title="Edit">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-icon btn-action-delete"
                                                onclick="deleteTutor({{ $tutor->id }})" title="Delete">
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

<script>
  function deleteTutor(tutorId) {
    // Show SweetAlert confirmation dialog
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
            // If confirmed, send AJAX request to delete the tutor
            $.ajax({
                url: '{{ route('tutors.delete', '') }}/' + tutorId,
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
        initDataTable('tutorsTable', {
            // Any custom options specific to this table
            order: [[1, 'asc']], // Sort by name column
            columnDefs: [
                { width: "5%", targets: [0] },       // Make # column narrow
                { width: "20%", targets: [1] },      // Name column width
                { width: "15%", targets: [2] },      // Designation column width
                { width: "45%", targets: [3] },      // Bio column width
                { width: "15%", targets: [4] },      // Actions column width
                { orderable: false, targets: [4] }   // Disable sorting on actions column
            ]
        });
    });
</script>
@endpush

@endsection


