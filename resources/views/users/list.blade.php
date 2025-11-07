@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2><i class="bx bx-group"></i> User Management</h2>
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> Add User
            </a>
        </div>

        <div class="card p-3 shadow-sm">
            <table id="usersTable" class="table table-striped table-bordered w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone No</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>


    <style>
        /* Add spacing between search box, pagination, and table */
        div.dataTables_wrapper {
            padding: 15px;
        }

        .dataTables_filter {
            margin-bottom: 15px;
            /* space below the search box */
        }

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

        /* Optional: Slightly rounded table and shadow */
        #usersTable {
            border-radius: 10px;
            overflow: hidden;
        }
    </style>

    <script>
        $(document).ready(function () {
    if (!$.fn.DataTable.isDataTable('#usersTable')) {
        $('#usersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('users.data') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'phone_no', name: 'phone_no' },
                {
                    data: 'created_at',
                    name: 'created_at',
                    render: function (data) {
                        if (!data) return '';
                        const date = new Date(data);
                        const month = ('0' + (date.getMonth() + 1)).slice(-2);
                        const day = ('0' + date.getDate()).slice(-2);
                        const year = date.getFullYear();
                        return `${month}-${day}-${year}`;
                    }
                },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            order: [[1, 'asc']], // optional: default sort by Name
            destroy: true // optional if table is being re-initialized
        });
    }
});


        function deleteUser(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This user record will be deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('users.delete', ':id') }}".replace(':id', id),
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire('Deleted!', response.message, 'success');
                                $('#usersTable').DataTable().ajax.reload();
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