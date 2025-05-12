@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center">SAT-ACT Course List</h1>
        <a href="{{ route('satact_course.create') }}" class="btn btn-primary mb-3">Add Sat/Act Course</a>

        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Package Name</th>
                <th>Price</th>
                <!-- <th>Number of Sessions</th> -->
                <th>Description</th>
                <!-- <th>Session Title</th> -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($SAT_ACT_Packages as $package)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $package->name }}</td>
                    <td>{{ $package->price }}</td>
                    <!-- <td>{{ $package->number_of_sessions }}</td> -->
                    <td>{{ $package->description }}</td>
                    <!-- <td>{{ optional($package->session)->title ?? 'N/A' }}</td> -->
                    <td>
                        <a href="{{ route('satact_course.show', $package->id) }}" class="btn btn-info btn-sm" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('satact_course.edit', $package->id) }}" class="btn btn-warning btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="#" class="btn btn-danger btn-sm" title="Delete"
                           onclick="deletePackage({{ $package->id }})">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function deletePackage(packageId) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to delete this package?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('satact_course.delete', '') }}/' + packageId,
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
</script>

@endsection
