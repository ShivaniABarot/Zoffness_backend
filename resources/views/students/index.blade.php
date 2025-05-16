@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Student List</h5>
            <a href="{{ route('students.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Add Student
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
                <table id="studentsTable" class="table table-striped table-hover datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Parent Name</th>
                            <th>Parent Email</th>
                            <th>Student Name</th>
                            <th>School</th>
                            <th class="text-center no-export">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $student->parent_name }}</td>
                                <td>{{ $student->parent_email }}</td>
                                <td>{{ $student->student_name }}</td>
                                <td>{{ $student->school }}</td>
                                <td class="text-center">
                                    @if(!empty($student->id))
                                        <div class="d-inline-flex">
                                            <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-icon btn-action-view" title="View">
                                                <i class="bx bx-show"></i>
                                            </a>
                                            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-icon btn-action-edit" title="Edit">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-icon btn-action-delete"
                                                    onclick="deleteStudent({{ $student->id }})" title="Delete">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="No Data" width="80" class="mb-3 opacity-50">
                                        <p class="mb-0">No students found.</p>
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
    function deleteStudent(studentId) {
        // Show SweetAlert confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to delete this student profile?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, send AJAX request to delete the student
                $.ajax({
                    url: '{{ route('students.delete', '') }}/' + studentId,
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
        initDataTable('studentsTable', {
            // Any custom options specific to this table
            order: [[3, 'asc']], // Sort by student name column
            columnDefs: [
                { width: "5%", targets: [0] },       // Make # column narrow
                { width: "15%", targets: [1] },      // Parent Name column width
                { width: "20%", targets: [2] },      // Parent Email column width
                { width: "15%", targets: [3] },      // Student Name column width
                { width: "30%", targets: [4] },      // School column width
                { width: "15%", targets: [5] },      // Actions column width
                { orderable: false, targets: [5] }   // Disable sorting on actions column
            ]
        });
    });
</script>
@endpush

@endsection