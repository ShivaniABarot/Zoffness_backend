@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center">Student List</h1>
        {{-- Remove this button if students are not created via this form --}}
         <a href="{{ route('students.create') }}" class="btn btn-primary mb-3">Add Student</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Parent Name</th>
                    <th>Parent Email</th>
                    <th>Student Name</th>
                    <th>School</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $student->parent_name }} </td>
                        <td>{{ $student->parent_email }}</td>
                        <td>{{ $student->student_name }}</td>
                        <td>{{ $student->school }}</td>

                        {{-- Remove or keep Actions if you do not support edit/delete --}}
                        <td>
                            @if(!empty($student->id))
                                <a href="{{ route('students.show', $student->id) }}" class="btn btn-info btn-sm" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-sm" title="Delete" onclick="deleteTutor({{ $student->id }})">
                            <i class="fas fa-trash"></i>
                        </a>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function deleteTutor(tutorId) {
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
                    // If confirmed, send AJAX request to delete the tutor
                    jQuery.ajax({
                        url: '{{ route('students.delete', '') }}/' + id, 
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}', // Include CSRF token
                        },
                        success: function (response) {
                            if (response.success) {
                                // Show success message and refresh the page or redirect
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    location.reload(); // Reload the page to update the list
                                });
                            } else {
                                // Show error message if tutor not found or couldn't delete
                                Swal.fire(
                                    'Error!',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function (xhr) {
                            // Handle errors, if any
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

@endsection