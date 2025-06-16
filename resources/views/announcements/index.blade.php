@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Announcements</h5>
            <a href="{{ route('announcements.create') }}" class="btn btn-success">
                <i class="bx bx-plus me-1"></i> New Announcement
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @elseif(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table id="announcementsTable" class="table table-striped table-hover datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Publish At</th>
                            <th>Status</th>
                            <th class="text-center no-export">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($announcements as $announcement)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $announcement->title }}</td>
                                <td>
                                    {{ $announcement->publish_at ? \Carbon\Carbon::parse($announcement->publish_at)->format('d M Y H:i') : '-' }}
                                </td>
                                <td>
                                    <span class="badge {{ $announcement->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $announcement->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('send.announcement') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $announcement->id }}">
                                        <button type="submit" class="btn btn-sm btn-primary" title="Send Announcement">
                                            <i class="bx bx-send"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="No Data" width="80" class="mb-3 opacity-50">
                                        <p class="mb-0">No announcements found.</p>
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

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        initDataTable('announcementsTable', {
            order: [[1, 'asc']],
            columnDefs: [
                { width: "5%", targets: [0] },
                { width: "35%", targets: [1] },
                { width: "20%", targets: [2] },
                { width: "20%", targets: [3] },
                { width: "20%", targets: [4], orderable: false }
            ]
        });
    });
</script>
@endpush

@endsection
