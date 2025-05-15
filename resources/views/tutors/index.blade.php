
@extends('layouts.app')

@section('content')
<div class="container">
    <h2> Tutors</h2>
    <a href="{{ route('tutors.create') }}" class="btn btn-primary mb-3">Add New Tutor</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Designation</th>
                <th>Bio</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tutors as $tutor)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $tutor->name }}</td>
                    <td>{{ $tutor->designation }}</td>
                    <td>{{ Str::limit($tutor->bio, 50) }}</td>
                    <!-- <td>
    @if ($tutor->image)
        <img src="{{ config('app.storage_url') . '/tutors/' . $tutor->image }}" alt="{{ $tutor->name }}" style="width: 50px; height: 50px; object-fit: cover;">
    @else
        <span>-</span>
    @endif
</td> -->
                    <td>
                        <a href="{{ route('tutors.show', $tutor->id) }}" class="btn btn-info btn-sm" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('tutors.edit', $tutor->id) }}" class="btn btn-warning btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="#" class="btn btn-danger btn-sm" title="Delete" onclick="deleteTutor({{ $tutor->id }})">
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
            jQuery.ajax({
                url: '{{ route('tutors.delete', '') }}/' + tutorId, // Construct URL dynamically
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}', // Include CSRF token
                },
                success: function(response) {
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


