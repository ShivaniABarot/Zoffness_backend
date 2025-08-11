@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
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
    <div class="mb-2 text-center">
        <h2 class="fw-bold mb-1">College Admission Counseling</h2>
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

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-body">
            <table id="collegeAdmissionTable" class="table table-striped table-bordered display responsive nowrap" style="width:100%" aria-describedby="collegeAdmissionTable_info">
                <thead>
                    <tr>
                        <th></th>
                        <th>#</th>
                        <th>Parent Name</th>
                        <th>Parent Phone</th>
                        <th>Parent Email</th>
                        <th>Student Name</th>
                        <th>Student Email</th>
                        <th>Package</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($collegeadmission as $admission)
                        <tr
                            data-created_at="{{ $admission->created_at }}"
                            data-package="{{ $admission->packages ?? 'N/A' }}"
                        >
                            <td class="details-control"></td>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-capitalize">{{ ($admission->parent_first_name ?? 'N/A') . ' ' . ($admission->parent_last_name ?? '') }}</td>
                            <td>
                                <a href="tel:{{ $admission->parent_phone ?? '#' }}" class="text-decoration-none text-primary">
                                    {{ $admission->parent_phone ?? 'N/A' }}
                                </a>
                            </td>
                            <td>
                                <a href="mailto:{{ $admission->parent_email ?? '#' }}" class="text-decoration-none text-primary">
                                    {{ $admission->parent_email ?? 'N/A' }}
                                </a>
                            </td>
                            <td class="text-capitalize">{{ ($admission->student_first_name ?? 'N/A') . ' ' . ($admission->student_last_name ?? '') }}</td>
                            <td>
                                @if($admission->student_email)
                                    <a href="mailto:{{ $admission->student_email }}" class="text-decoration-none text-primary">{{ $admission->student_email }}</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill" style="font-size: 0.75rem; font-weight: 500;">
                                    {!! $admission->packages 
                                        ? implode('<br>', explode(' ', $admission->packages, 3)) 
                                        : 'N/A' 
                                    !!}
                                </span>
                            </td>
                            <td class="fw-semibold">${{ isset($admission->subtotal) ? number_format($admission->subtotal, 2) : '0.00' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="text-muted">
                                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="No Data" width="80" class="mb-3 opacity-50">
                                    <p class="mb-0">No college admission counseling records found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script>
        $(document).ready(function() {
            if ($('#collegeAdmissionTable').length) {
                function format(data) {
                    let createdAtHtml = '';
                    try {
                        const dateObj = new Date(data.createdAt);
                        if (isNaN(dateObj.getTime())) {
                            createdAtHtml = 'Invalid Date';
                        } else {
                            createdAtHtml = dateObj.toLocaleString('en-US', {
                                day: '2-digit',
                                month: 'long',
                                weekday: 'long',
                                // hour: 'numeric',
                                // minute: '2-digit',
                                // hour12: true
                            });
                        }
                    } catch {
                        createdAtHtml = 'Invalid Date';
                    }

                    return `
                        <div class="p-3 bg-light border rounded">
                            <h6>College Admission Details</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Date:</strong></td>
                                    <td>${createdAtHtml}</td>
                                </tr>
                            </table>
                        </div>`;
                }

                const table = $('#collegeAdmissionTable').DataTable({
                    order: [[1, 'asc']],
                    columnDefs: [
                        {
                            className: 'details-control',
                            orderable: false,
                            data: null,
                            defaultContent: '',
                            targets: 0
                        },
                        { className: 'fw-semibold', targets: [7] },
                        { className: 'text-center', targets: [6, 7] }
                    ],
                    responsive: true,
                    pageLength: 10,
                    lengthMenu: [5, 10, 25, 50, 100],
                    dom: 'Bfrtip',
                    buttons: ['excel', 'pdf']
                });

                $('#collegeAdmissionTable tbody').on('click', 'td.details-control', function () {
                    const tr = $(this).closest('tr');
                    const row = table.row(tr);

                    if (row.child.isShown()) {
                        row.child.hide();
                        tr.removeClass('shown');
                    } else {
                        row.child(format({
                            package: tr.data('package'),
                            createdAt: tr.data('created_at')
                        })).show();
                        tr.addClass('shown');
                    }
                });
            }
        });
    </script>
@endpush
@endsection
