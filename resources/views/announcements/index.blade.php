@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Announcements</h5>
            <a href="{{ route('announcements.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Add Announcement
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
                <table id="announcementsTable" class="table table-striped table-hover datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Message</th>
                            <th>Publish Date</th>
                            <th class="text-center no-export">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($announcements as $announcement)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $announcement->title ?? 'N/A' }}</td>
                                <td>{{ Str::limit($announcement->message, 80) ?? 'N/A' }}</td>
                                <td>{{ $announcement->publish_at ? $announcement->publish_at->format('d M Y h:i A') : 'Immediately' }}</td>
                                <td class="text-center">
                                    @if(!empty($announcement->id))
                                        <div class="d-inline-flex">
                                            <a href="{{ route('announcements.show', $announcement->id) }}" class="btn btn-sm btn-icon btn-action-view" title="View">
                                                <i class="bx bx-show"></i>
                                            </a>
                                            <a href="{{ route('announcements.edit', $announcement->id) }}" class="btn btn-sm btn-icon btn-action-edit" title="Edit">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-icon btn-action-delete"
                                                    onclick="deleteAnnouncement({{ $announcement->id }})" title="Delete">
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
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="No Data" width="80" class="mb-3 opacity-50">
                                        <p class="mb-0">No announcements available.</p>
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
    function deleteAnnouncement(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to delete this announcement?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('announcements.delete', '') }}/' + id,
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

@push('scripts')
<script>
    $(document).ready(function() {
        initDataTable('announcementsTable', {
            order: [[1, 'asc']],
            columnDefs: [
                { width: "5%", targets: [0] },
                { width: "20%", targets: [1] },
                { width: "35%", targets: [2] },
                { width: "20%", targets: [3] },
                { width: "20%", targets: [4], orderable: false }
            ]
        });
    });
</script>
@endpush
@endsection
