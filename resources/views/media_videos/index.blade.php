@extends('layouts.app')
@section('title', 'Zoffness Media')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bx bx-video"></i> Zoffness Media</h2>
        <a href="{{ route('media-videos.create') }}" class="btn btn-primary">
            <i class="bx bx-plus"></i> Add New Video
        </a>
    </div>

    <div class="card p-3 shadow-sm">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <table id="mediaVideosTable" class="table table-striped table-bordered w-100">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Video URL</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Order</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    div.dataTables_wrapper {
        padding: 15px;
    }
    .dataTables_filter,
    .dataTables_length {
        margin-bottom: 15px;
    }
    table.dataTable {
        margin-top: 10px !important;
        margin-bottom: 20px !important;
    }
    .dataTables_paginate {
        margin-top: 15px;
    }
    .dataTables_info {
        margin-top: 10px;
    }
    #mediaVideosTable {
        border-radius: 10px;
        overflow: hidden;
    }
    .video-link {
        color: #0d6efd;
        text-decoration: none;
    }
    .video-link:hover {
        text-decoration: underline;
    }
    .badge {
        font-size: 0.75em;
    }
</style>

<script>
    $(document).ready(function () {
        if (!$.fn.DataTable.isDataTable('#mediaVideosTable')) {
            $('#mediaVideosTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('media-videos.data') }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'title', name: 'title' },
                    { 
                        data: 'video_url', 
                        name: 'video_url',
                        render: function(data, type, row) {
                            return data ? `<a href="${data}" target="_blank" class="video-link">Watch Video</a>` : '—';
                        }
                    },
                    { 
                        data: 'description', 
                        name: 'description',
                        render: function(data, type, row) {
                            return data ? data.substring(0, 50) + (data.length > 50 ? '...' : '') : '—';
                        }
                    },
                    { 
                        data: 'status', 
                        name: 'is_active', 
                        orderable: false, 
                        searchable: false 
                    },
                    { data: 'order', name: 'order' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                order: [[5, 'asc']], // Order by 'order' column
                destroy: true
            });
        }
    });

    function deleteVideo(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This video will be permanently deleted.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('media-videos.destroy', ':id') }}".replace(':id', id),
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire('Deleted!', response.message, 'success');
                            $('#mediaVideosTable').DataTable().ajax.reload();
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error!', 'An unexpected error occurred.', 'error');
                    }
                });
            }
        });
    }
</script>
@endsection