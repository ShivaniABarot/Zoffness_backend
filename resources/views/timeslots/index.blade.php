@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center">Timeslot List</h1>
        <a href="{{ route('timeslots.create') }}" class="btn btn-primary mb-3">Add Timeslot</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Session Title</th>
                    <th>Room</th>
                    <th>Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Available Seats</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($timeslots as $timeslot)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $timeslot->session->title }}</td>
                        <td>{{ $timeslot->room }}</td>
                        <td>{{ $timeslot->date }}</td>
                        <td>{{ $timeslot->start_time }}</td>
                        <td>{{ $timeslot->end_time }}</td>
                        <td>{{ $timeslot->available_seats }}</td>
                        <td>
                            <a href="{{ route('timeslots.show', $timeslot->id) }}" class="btn btn-info btn-sm" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('timeslots.edit', $timeslot->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#" class="btn btn-danger btn-sm" title="Delete"
                                onclick="deleteTimeslot({{ $timeslot->id }})">
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
      function deleteTimeslot(TSID) {
        // Show SweetAlert confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to delete this timeslot?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, send AJAX request to delete the tutor
                jQuery.ajax({
                    url: '{{ route('timeslots.delete', '') }}/' + TSID, // Construct URL dynamically
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
