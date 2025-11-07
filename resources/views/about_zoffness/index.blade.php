@extends('layouts.app')
@section('title', 'About Zoffness College Prep')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-building"></i> About Zoffness College Prep</h2>
        <a href="{{ route('about-zoffness.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add About Section
        </a>
    </div>

    <div class="card p-3 shadow-sm">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <table id="aboutTable" class="table table-striped table-bordered w-100">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Main Image</th>
                    <th>Image 1</th>
                    <th>Image 2</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>CTA Text</th>
                    <th>CTA Link</th>
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
    #aboutTable {
        border-radius: 10px;
        overflow: hidden;
    }
    img.about-img {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }
</style>

<script>
$(document).ready(function () {
    if (!$.fn.DataTable.isDataTable('#aboutTable')) {
        $('#aboutTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('about-zoffness.data') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                {
                    data: 'image_main',
                    name: 'image_main',
                    render: function (data) {
                        return data ? `<img src="{{ asset('storage') }}/${data}" class="about-img" alt="Main Image">` : '';
                    },
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'image_1',
                    name: 'image_1',
                    render: function (data) {
                        return data ? `<img src="{{ asset('storage') }}/${data}" class="about-img" alt="Image 1">` : '';
                    },
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'image_2',
                    name: 'image_2',
                    render: function (data) {
                        return data ? `<img src="{{ asset('storage') }}/${data}" class="about-img" alt="Image 2">` : '';
                    },
                    orderable: false,
                    searchable: false
                },
                { data: 'title', name: 'title' },
                { 
                    data: 'description', 
                    name: 'description',
                    render: function (data) {
                        return data.length > 100 ? data.substring(0, 100) + '...' : data;
                    }
                },
                { data: 'cta_text', name: 'cta_text' },
                { 
                    data: 'cta_link', 
                    name: 'cta_link',
                    render: function (data) {
                        return data ? `<a href="${data}" target="_blank">${data}</a>` : '';
                    }
                },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            order: [[4, 'asc']],
            destroy: true
        });
    }
});

function deleteAbout(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This section will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('about-zoffness.destroy', ':id') }}".replace(':id', id), // ✅ Fixed
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function (response) {
                    if (response.success) {
                        Swal.fire('Deleted!', response.message, 'success');
                        $('#aboutTable').DataTable().ajax.reload();
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
