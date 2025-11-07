@extends('layouts.app')
@section('title', 'Our Programs')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2><i class="bi bi-journal"></i> Our Programs</h2>
            <a href="{{ route('programs.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add Program
            </a>
        </div>

        <div class="card p-3 shadow-sm">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <table id="programsTable" class="table table-striped table-bordered w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Icon</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Link</th>
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

        #programsTable {
            border-radius: 10px;
            overflow: hidden;
        }

        img.program-icon {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }
    </style>

    <script>
        $(document).ready(function () {
            if (!$.fn.DataTable.isDataTable('#programsTable')) {
                $('#programsTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('programs.data') }}',
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        {
                            data: 'icon_image',
                            name: 'icon_image',
                            render: function (data, type, row) {
                                return data ? `<img src="{{ asset('storage') }}/${data}" class="program-icon" alt="Icon">` : '';
                            },
                            orderable: false,
                            searchable: false
                        },
                        { data: 'title', name: 'title' },
                        { data: 'short_description', name: 'short_description' },
                        {
                            data: 'link',
                            name: 'link',
                            render: function (data, type, row) {
                                return data ? `<a href="${data}" target="_blank">${data}</a>` : '';
                            }
                        },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false }
                    ],
                    order: [[2, 'asc']],
                    destroy: true
                });
            }
        });

        function deleteProgram(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This program will be permanently deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('programs.destroy', ':id') }}".replace(':id', id),
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire('Deleted!', response.message, 'success');
                                $('#programsTable').DataTable().ajax.reload();
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