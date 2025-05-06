<!-- resources/views/students/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Session List</h1>
    <a href="{{ route('sessions.create') }}" class="btn btn-primary mb-3">Add Session</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Type</th>
                {{-- <th>Tutor</th> --}}
                <th>Price Per Slot</th>
                <th>Max Capacity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sessions as $session)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $session->title }}</td>
                    <td>{{ ucfirst($session->session_type) }}</td>
                    {{-- <td>{{ $session->tutor->name }}</td> --}}
                    <td>${{ $session->price_per_slot }}</td>
                    <td>{{ $session->max_capacity }}</td>
                    <td>
                        <a href="{{ route('sessions.show', $session->id) }}" class="btn btn-info btn-sm" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('sessions.edit', $session->id) }}" class="btn btn-warning btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                        <a href="#" class="btn btn-danger btn-sm" title="Delete" onclick="deleteSession({{ $session->id }})">
                            <i class="fas fa-trash"></i>
                        </a>
                        
                    </td>
                   
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
            jQuery.ajax({
                url: '{{ route('sessions.delete', '') }}/' + sessionId, // Construct URL dynamically
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}', // Include CSRF token
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message and refresh the page
                        Swal.fire(
                            'Deleted!', response.message,
                            'success'
                        ).then(() => {
                            location.reload(); // Reload the page to update the list
                        });
                    } else {
                        // Show error message if session couldn't be deleted
                        Swal.fire(
                            'Error!',
                            response.message,
                            'error'
                        );
                    }
                },
                error: function(xhr) {
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
