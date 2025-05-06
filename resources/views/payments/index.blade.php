@extends('layouts.app')

@section('title', 'Enrollment List')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
@endpush

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Enrollment List</h2>
                @can('create', App\Models\Enroll::class)
                    <a href="{{ route('enroll.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> New Enrollment
                    </a>
                @endcan
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered w-100" id="enrollTable">
                        <thead class="bg-light">
                            <tr>
                                <th>Parent Name</th>
                                <th>Parent Phone</th>
                                <th>Parent Email</th>
                                <th>Student Name</th>
                                <th>Student Email</th>
                                <th>School</th>
                                <th>Total Amount</th>
                                <th>Packages</th>
                                <th width="100">Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const table = $('#enrollTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: @json(route('enroll.data')), // This will now point to /inquiry/enroll/data
                    error: function(xhr, error, thrown) {
                        console.error('DataTables error:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Failed to load enrollment data. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    }
                },

                columns: [{
                        data: 'parent_name',
                        name: 'parent_name'
                    },
                    {
                        data: 'parent_phone',
                        name: 'parent_phone',
                        render: function(data) {
                            return `<a href="tel:${data}" class="text-decoration-none">${data}</a>`;
                        }
                    },
                    {
                        data: 'parent_email',
                        name: 'parent_email',
                        render: function(data) {
                            return `<a href="mailto:${data}" class="text-decoration-none">${data}</a>`;
                        }
                    },
                    {
                        data: 'student_name',
                        name: 'student_name'
                    },
                    {
                        data: 'student_email',
                        name: 'student_email',
                        render: function(data) {
                            return data ?
                                `<a href="mailto:${data}" class="text-decoration-none">${data}</a>` :
                                '-';
                        }
                    },
                    {
                        data: 'school',
                        name: 'school'
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount',
                        render: function(data) {
                            return new Intl.NumberFormat('en-US', {
                                style: 'currency',
                                currency: 'USD'
                            }).format(data);
                        }
                    },
                    {
                        data: 'packages',
                        name: 'packages'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                order: [
                    [0, 'asc']
                ],
                pageLength: 25,
                language: {
                    search: "Search:",
                    processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
                }
            });

            // Refresh table data every 5 minutes
            setInterval(() => {
                table.ajax.reload(null, false);
            }, 300000);
        });
    </script>
@endpush
