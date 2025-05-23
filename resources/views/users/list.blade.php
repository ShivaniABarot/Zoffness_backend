@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">User List</h5>
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Create User
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
                <table id="usersTable" class="table table-striped table-hover datatable">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-center no-export">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-label-{{ $user->role == 'admin' ? 'primary' : ($user->role == 'tutor' ? 'success' : 'info') }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex">
                                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-icon btn-action-view" title="View">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-icon btn-action-edit" title="Edit">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-icon btn-action-delete"
                                                onclick="deleteUser({{ $user->id }})" title="Delete">
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
                                        <p class="mb-0">No users found.</p>
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
  function deleteUser(userID) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to delete this user?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route('users.delete', '') }}/' + userID,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire(
                            'Deleted!',
                            response.message || 'User deleted successfully.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            response.message || 'Failed to delete user.',
                            'error'
                        );
                    }
                },
                error: function(xhr) {
                    Swal.fire(
                        'Error!',
                        xhr.responseJSON?.message || 'An unexpected error occurred.',
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
        initDataTable('usersTable', {
            order: [[1, 'asc']],
            columnDefs: [
                { orderable: false, targets: [0, 4] }, // Disable sorting on Sr No and Actions
                { className: 'text-center', targets: [0, 3, 4] } // Center Sr No, Role, and Actions
            ]
        });
    });
</script>
@endpush

@endsection