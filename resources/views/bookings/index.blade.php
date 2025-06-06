@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Bookings</h1>
        <a href="{{ route('bookings.create') }}" class="btn btn-primary">Create Booking</a>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Session</th>
                    <th>Timeslot</th>
                    {{-- <th>Student</th> --}}
                    {{-- <th>Parent</th> --}}
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bookings as $booking)
                    <tr>
                        <td>{{ $booking->session->title }}</td>
                        <td>{{ $booking->timeslot->start_time }} - {{ $booking->timeslot->end_time }}</td>
                        {{-- <td>{{ $booking->student->name }}</td> --}}
                        {{-- <td>{{ $booking->parent->name }}</td> --}}
                        <td>{{ $booking->status }}</td>
                        <td>
                            <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-info btn-sm" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-warning btn-sm"
                                title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#" class="btn btn-danger btn-sm" title="Delete"
                                onclick="deleteBooking({{ $booking->id }})">
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
    function deleteBooking(bookingID) {
      // Show SweetAlert confirmation dialog
      Swal.fire({
          title: 'Are you sure?',
          text: 'Do you want to delete this booking?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'No, cancel!',
          reverseButtons: true
      }).then((result) => {
          if (result.isConfirmed) {
              // If confirmed, send AJAX request to delete the booking
              jQuery.ajax({
                  url: '{{ route('bookings.delete', '') }}/' + bookingID, // Construct URL dynamically
                  type: 'POST', // Change to POST to workaround DELETE issue in some browsers
                  data: {
                      _method: 'DELETE', // Tell Laravel to treat this as a DELETE request
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
                          // Show error message if booking couldn't be deleted
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
