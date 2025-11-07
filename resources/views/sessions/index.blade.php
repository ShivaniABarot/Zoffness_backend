@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2><i class="bx bx-calendar"></i> Session Management</h2>
            <a href="{{ route('sessions.create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> Add Session
            </a>
        </div>

        <div class="card p-3 shadow-sm">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <table id="sessionsTable" class="table table-striped table-bordered w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Price Per Slot</th>
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

    <style>
        /* Add spacing between search box, pagination, and table */
        div.dataTables_wrapper {
            padding: 15px;
        }

        .dataTables_filter {
            margin-bottom: 15px;
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
        #sessionsTable {
            border-radius: 10px;
            overflow: hidden;
        }

        /* Switch Styling */
        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 26px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #e74c3c; /* red default */
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #2ecc71; /* green when active */
        }

        input:checked + .slider:before {
            transform: translateX(24px);
        }
    </style>

    <script>
        $(document).ready(function () {
            if (!$.fn.DataTable.isDataTable('#sessionsTable')) {
                $('#sessionsTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('sessions.data') }}',

                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'title', name: 'title' },
                        { data: 'formatted_date', name: 'date' },
                        { data: 'formatted_price', name: 'price_per_slot' },
                        { data: 'status', name: 'status', orderable: false, searchable: false },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false }
                    ],
                    order: [[1, 'asc']], // Default sort by Title
                    destroy: true // Optional if table is being re-initialized
                });
            }
        });

        function deleteSession(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This session record will be deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('sessions.delete', ':id') }}".replace(':id', id),
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire('Deleted!', response.message, 'success');
                                $('#sessionsTable').DataTable().ajax.reload();
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

        function toggleStatus(id, currentStatus) {
            const newStatus = currentStatus === 'active' ? 'in-active' : 'active';
            const confirmText = `Are you sure you want to ${newStatus.toLowerCase()} this session?`;

            Swal.fire({
                title: 'Change Status',
                text: confirmText,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: `Yes, ${newStatus}`,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/sessions/${id}/toggle-status`,
                        method: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire('Updated!', response.message, 'success');
                                $('#sessionsTable').DataTable().ajax.reload();
                            } else {
                                Swal.fire('Error!', 'Could not update status.', 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error!', 'An error occurred while updating status.', 'error');
                        }
                    });
                } else {
                    $('#sessionsTable').DataTable().ajax.reload(); // Reset switch if canceled
                }
            });
        }
    </script>
@endsection