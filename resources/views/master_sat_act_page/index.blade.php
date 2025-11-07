@extends('layouts.app')
@section('title', 'Master SAT/ACT Page')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2><i class="bi bi-book-half"></i> Master SAT/ACT Page</h2>
            <a href="{{ route('master_sat_act_page.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add Content
            </a>

        </div>

        <div class="card p-3 shadow-sm">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <table id="satActTable" class="table table-striped table-bordered w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Section Type</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Status</th>
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

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

        img.page-thumb {
            border-radius: 8px;
            object-fit: cover;
        }
    </style>

    <script>
        $(document).ready(function () {
            if (!$.fn.DataTable.isDataTable('#satActTable')) {
                $('#satActTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('master_sat_act_page.data') }}',
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'section_type', name: 'section_type' },
                        { data: 'title', name: 'title' },
                        {
                            data: 'description',
                            name: 'description',
                            render: function (data) {
                                return data && data.length > 100 ? data.substring(0, 100) + '...' : data;
                            }
                        },
                        {
                            data: 'image_preview',
                            name: 'image_preview',
                            orderable: false,
                            searchable: false,
                            render: function (data) {
                                return data ? data : '<span class="text-muted">No Image</span>';
                            }
                        },
                        {
                            data: 'status_label',
                            name: 'status_label',
                            orderable: false,
                            searchable: false
                        },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false }
                    ],
                    order: [[1, 'asc']],
                    destroy: true
                });
            }
        });

        function deleteRecord(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This record will be permanently deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('master_sat_act_page.destroy', ':id') }}".replace(':id', id),
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire('Deleted!', response.message, 'success');
                                $('#satActTable').DataTable().ajax.reload();
                            } else {
                                Swal.fire('Error!', response.message, 'error');
                            }
                        },
                        error: function (xhr) {
                            let message = xhr.responseJSON?.message || 'An unexpected error occurred.';
                            Swal.fire('Error!', message, 'error');
                        }
                    });
                }
            });
        }
    </script>
@endsection