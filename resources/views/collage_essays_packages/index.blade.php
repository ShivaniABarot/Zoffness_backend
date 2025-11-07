@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bx bx-book-alt"></i> College Essays Packages</h2>
        <a href="{{ route('collage_essays_packages.create') }}" class="btn btn-primary">
            <i class="bx bx-plus"></i> Add Package
        </a>
    </div>

    <div class="card p-3 shadow-sm">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <table id="collegeAdmissionTable" class="table table-striped table-bordered w-100">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Date</th>
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
    div.dataTables_wrapper { padding: 15px; }
    .dataTables_filter, .dataTables_length { margin-bottom: 15px; }
    table.dataTable { margin-top: 10px !important; margin-bottom: 20px !important; }
    .dataTables_paginate { margin-top: 15px; }
    .dataTables_info { margin-top: 10px; }
    #collegeAdmissionTable { border-radius: 10px; overflow: hidden; }

    .switch { position: relative; display: inline-block; width: 50px; height: 26px; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #e74c3c; transition: .4s; border-radius: 34px; }
    .slider:before { position: absolute; content: ""; height: 20px; width: 20px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
    input:checked + .slider { background-color: #2ecc71; }
    input:checked + .slider:before { transform: translateX(24px); }
</style>

<script>
$(document).ready(function () {
    if (!$.fn.DataTable.isDataTable('#collegeAdmissionTable')) {
        $('#collegeAdmissionTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("collage_essays_packages.data") }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'price', name: 'price' },
                { data: 'description', name: 'description' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'created_at', name: 'created_at' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            order: [[1, 'asc']],
            destroy: true
        });
    }
});

function deletePackage(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This package will be deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('collage_essays_packages.delete', ':id') }}".replace(':id', id),
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function (response) {
                    if (response.success) {
                        Swal.fire('Deleted!', response.message, 'success');
                        $('#collegeAdmissionTable').DataTable().ajax.reload();
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
    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';

    Swal.fire({
        title: 'Change Status',
        text: `Are you sure you want to ${newStatus} this package?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: `Yes, ${newStatus}`,
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('collage_essays_packages.toggleStatus', ':id') }}".replace(':id', id),
                method: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Updated!', response.message, 'success');
                        $('#collegeAdmissionTable').DataTable().ajax.reload();
                    } else {
                        Swal.fire('Error!', 'Could not update status.', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'An error occurred while updating status.', 'error');
                }
            });
        } else {
            $('#collegeAdmissionTable').DataTable().ajax.reload();
        }
    });
}
</script>
@endsection
