@extends('layouts.app')
@section('title', 'Hero Banners')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bx bx-image-alt"></i> Hero Banners</h2>
        <a href="{{ route('hero-banners.create') }}" class="btn btn-primary">
            <i class="bx bx-plus"></i> Add New Banner
        </a>
    </div>

    <div class="card p-3 shadow-sm">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <table id="heroBannersTable" class="table table-striped table-bordered w-100">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tagline</th>
                    <th>Background Image</th>
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
    #heroBannersTable {
        border-radius: 10px;
        overflow: hidden;
    }
    img.banner-thumb {
        width: 80px;
        height: 50px;
        object-fit: cover;
        border-radius: 5px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
</style>

<script>
    $(document).ready(function () {
        if (!$.fn.DataTable.isDataTable('#heroBannersTable')) {
            $('#heroBannersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('hero-banners.data') }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'tagline', name: 'tagline' },
                    { 
                        data: 'background_image', 
                        name: 'background_image',
                        render: function(data, type, row) {
                            return `<img src="{{ asset('storage') }}/${data}" class="banner-thumb" alt="Hero Banner">`;
                        },
                        orderable: false,
                        searchable: false
                    },
                    { data: 'cta_text', name: 'cta_text' },
                    { 
                        data: 'cta_link', 
                        name: 'cta_link',
                        render: function(data, type, row) {
                            return data ? `<a href="${data}" target="_blank">${data}</a>` : '';
                        }
                    },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                order: [[1, 'asc']],
                destroy: true
            });
        }
    });

    function deleteBanner(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This banner will be permanently deleted.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('hero-banners.destroy', ':id') }}".replace(':id', id),
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire('Deleted!', response.message, 'success');
                            $('#heroBannersTable').DataTable().ajax.reload();
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
