@extends('layouts.app')
@section('title', 'Testimonials')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-chat-quote"></i> Testimonials</h2>
        <a href="{{ route('testimonials.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Testimonial
        </a>
    </div>

    <div class="card p-3 shadow-sm">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <table id="testimonialTable" class="table table-striped table-bordered w-100">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Relationship</th>
                    <th>Rating</th>
                    <th>Testimonial</th>
                    <th>Consent</th>
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
    div.dataTables_wrapper { padding: 15px; }
    .dataTables_filter, .dataTables_length { margin-bottom: 15px; }
    table.dataTable { margin-top: 10px !important; margin-bottom: 20px !important; }
    .dataTables_paginate { margin-top: 15px; }
    .dataTables_info { margin-top: 10px; }
</style>

<script>
$(document).ready(function () {
    if (!$.fn.DataTable.isDataTable('#testimonialTable')) {
        $('#testimonialTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('testimonials.data') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'relationship', name: 'relationship' },
                { data: 'rating', name: 'rating' },
                { 
                    data: 'testimonial', 
                    name: 'testimonial',
                    render: function(data) {
                        return data && data.length > 100 ? data.substring(0, 100) + '...' : data;
                    }
                },
                { 
                    data: 'consent', 
                    name: 'consent',
                    render: function(data) {
                        return data ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>';
                    }
                },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            order: [[1, 'asc']],
            destroy: true
        });
    }
});

function deleteTestimonial(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This testimonial will be permanently deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('testimonials.destroy', ':id') }}".replace(':id', id),
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function (response) {
                    if (response.success) {
                        Swal.fire('Deleted!', response.message, 'success');
                        $('#testimonialTable').DataTable().ajax.reload();
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
