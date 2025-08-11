@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<style>
    td.details-control {
        background: url('https://datatables.net/examples/resources/details_open.png') no-repeat center center;
        cursor: pointer;
    }
    tr.shown td.details-control {
        background: url('https://datatables.net/examples/resources/details_close.png') no-repeat center center;
    }
    table.dataTable td {
        vertical-align: middle;
    }
    table.dataTable thead th {
        background-color: #f9f9f9;
        font-weight: bold;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="mb-4 text-center">
        <h2 class="fw-bold mb-2" style="color: #566a7f;">SAT/ACT Course List</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border rounded-4 overflow-hidden">
        <div class="card-header bg-transparent border-0 pt-4 pb-0">
            <div class="d-flex justify-content-between align-items-center">
                @can('create', App\Models\SAT_ACT_Course::class)
                    <a href="{{ route('sat_act_course.create') }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-plus me-1"></i> Add New
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body pt-0">
            <table id="satActCourseTable" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th></th>
                        <th>#</th>
                        <th>Parent Name</th>
                        <th>Parent Phone</th>
                        <th>Parent Email</th>
                        <th>Student Name</th>
                        <th>Student Email</th>
                        <th>School</th>
                        <th>Total Amount</th>
                        <th>Package</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sat_act_course as $course)
                        <tr data-exam-date="{{ $course->exam_date ?? $course->created_at }}"
                            data-package="{{ $course->package_name ?? 'N/A' }}"
                            data-created-at="{{ $course->created_at }}">
                            <td class="details-control">
                                <img src="https://cdn-icons-png.flaticon.com/512/190/190411.png" alt="status" width="18">
                            </td>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-capitalize">{{ ($course->parent_firstname ?? 'N/A') . ' ' . ($course->parent_lastname ?? '') }}</td>
                            <td>
                                <a href="tel:{{ $course->parent_phone ?? '#' }}" class="text-decoration-none text-primary">
                                    {{ $course->parent_phone ?? 'N/A' }}
                                </a>
                            </td>
                            <td>
                                <a href="mailto:{{ $course->parent_email ?? '#' }}" class="text-decoration-none text-primary">
                                    {{ $course->parent_email ?? 'N/A' }}
                                </a>
                            </td>
                            <td class="text-capitalize">{{ ($course->student_firstname ?? 'N/A') . ' ' . ($course->student_lastname ?? '') }}</td>
                            <td>
                                @if($course->student_email)
                                    <a href="mailto:{{ $course->student_email }}" class="text-decoration-none text-primary">{{ $course->student_email }}</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $course->school ?? 'N/A' }}</td>
                            <td class="fw-semibold">${{ isset($course->amount) ? number_format($course->amount, 2) : '0.00' }}</td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill" style="font-size: 0.85rem;">
                                    {{ $course->package_name ?? 'N/A' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <div class="text-muted">
                                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="No Data" width="80" class="mb-3 opacity-50">
                                    <p class="mb-0">No SAT/ACT courses found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @once
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @endonce
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script>
        $(document).ready(function () {
            function format(data) {
                let examDate = data.examDate;
                try {
                    examDate = examDate !== 'N/A' ? new Date(examDate).toLocaleString('en-US', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric',
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true
                    }) : 'N/A';
                } catch {
                    examDate = 'Invalid Date';
                }

                let registrationDate = data.createdAt;
                try {
                    registrationDate = registrationDate !== 'N/A' ? new Date(registrationDate).toLocaleDateString('en-US', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    }) : 'N/A';
                } catch {
                    registrationDate = 'Invalid Date';
                }

                return `
                    <div class="p-3 bg-light border rounded">
                        <h6>Course Details</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Package:</strong></td>
                                <td>${data.package}</td>
                            </tr>
                            <tr>
                                <td><strong>Registration Date:</strong></td>
                                <td>${registrationDate}</td>
                            </tr>
                        </table>
                    </div>`;
            }

            const table = $('#satActCourseTable').DataTable({
                order: [[1, 'asc']],
                columnDefs: [
                    {
                        className: 'details-control',
                        orderable: false,
                        data: null,
                        defaultContent: '',
                        targets: 0
                    },
                    { className: 'fw-semibold', targets: [8] }, // Total Amount
                    { className: 'text-center', targets: [9] }  // Package
                ],
                responsive: true,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50, 100],
                dom: 'Bfrtip',
                buttons: ['excel', 'pdf']
            });

            $('#satActCourseTable tbody').on('click', 'td.details-control', function () {
                const tr = $(this).closest('tr');
                const row = table.row(tr);

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    row.child(format({
                        examDate: tr.data('exam-date'),
                        package: tr.data('package'),
                        createdAt: tr.data('created-at')
                    })).show();
                    tr.addClass('shown');
                }
            });
        });
    </script>
@endpush
