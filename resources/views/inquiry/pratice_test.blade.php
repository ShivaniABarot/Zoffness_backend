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
    <div class="mb-2 text-center">
        <h2 class="fw-bold mb-1">Practice Test & Analysis List</h2>
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
            <table id="practiceTestTable" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th></th>
                        <th>#</th>
                        <th>Parent Name</th>
                        <th>Parent Phone</th>
                        <th>Parent Email</th>
                        <th>Student Name</th>
                        <th>Student Email</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($praticetests as $praticetest)
                        <tr
                            data-date="{{ json_encode($praticetest->date) }}"
                            data-package="{!! $praticetest->packages && $praticetest->packages->count() ? $praticetest->packages->pluck('name')->implode(', ') : 'N/A' !!}"
                        >
                            <td class="details-control"></td>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-capitalize">{{ ($praticetest->parent_first_name ?? 'N/A') . ' ' . ($praticetest->parent_last_name ?? '') }}</td>
                            <td>
                                <a href="tel:{{ $praticetest->parent_phone ?? '#' }}" class="text-decoration-none text-primary">
                                    {{ $praticetest->parent_phone ?? 'N/A' }}
                                </a>
                            </td>
                            <td>
                                <a href="mailto:{{ $praticetest->parent_email ?? '#' }}" class="text-decoration-none text-primary">
                                    {{ $praticetest->parent_email ?? 'N/A' }}
                                </a>
                            </td>
                            <td class="text-capitalize">{{ ($praticetest->student_first_name ?? 'N/A') . ' ' . ($praticetest->student_last_name ?? '') }}</td>
                            <td>
                                @if($praticetest->student_email)
                                    <a href="mailto:{{ $praticetest->student_email }}" class="text-decoration-none text-primary">{{ $praticetest->student_email }}</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="fw-semibold">${{ isset($praticetest->subtotal) ? number_format($praticetest->subtotal, 2) : '0.00' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <div class="text-muted">
                                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="No Data" width="80" class="mb-3 opacity-50">
                                    <p class="mb-0">No practice tests found.</p>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
    $(document).ready(function () {
        function format(data) {
            let datesHtml = '';
            try {
                const dates = JSON.parse(data.date);
                if (Array.isArray(dates) && dates.length > 0) {
                    datesHtml = dates.map((d, i) => {
                        try {
                            // Parse YYYY-MM-DD date
                            const dateObj = new Date(d);
                            if (isNaN(dateObj.getTime())) {
                                return `${i + 1}. Invalid Date`;
                            }
                            const options = {
                                day: '2-digit',
                                month: 'long',
                                weekday: 'long',
                                hour: 'numeric',
                                minute: '2-digit',
                                hour12: true
                            };
                            // Since YYYY-MM-DD doesn't include time, hours/minutes will be 00:00
                            const formattedDate = dateObj.toLocaleString('en-US', {
                                day: '2-digit',
                                month: 'long',
                                weekday: 'long'
                            });
                            return `${i + 1}. ${formattedDate}`;
                        } catch {
                            return `${i + 1}. Invalid Date`;
                        }
                    }).join('<br>');
                } else {
                    datesHtml = 'N/A';
                }
            } catch {
                datesHtml = 'Invalid Dates';
            }

            return `
                <div class="p-3 bg-light border rounded">
                    <h6>Practice Test Details</h6>
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Package:</strong></td>
                            <td>${data.package}</td>
                        </tr>
                        <tr>
                            <td><strong>Exam Dates:</strong></td>
                            <td>${datesHtml}</td>
                        </tr>
                    </table>
                </div>`;
        }

        const table = $('#practiceTestTable').DataTable({
            order: [[1, 'asc']],
            columnDefs: [
                {
                    className: 'details-control',
                    orderable: false,
                    data: null,
                    defaultContent: '',
                    targets: 0
                },
                { className: 'fw-semibold', targets: [5] },
                { className: 'text-center', targets: [6] }
            ],
            responsive: true,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            dom: 'Bfrtip',
            buttons: ['excel', 'pdf']
        });

        $('#practiceTestTable tbody').on('click', 'td.details-control', function () {
            const tr = $(this).closest('tr');
            const row = table.row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            } else {
                row.child(format({
                    package: tr.data('package'),
                    date: tr.data('date')
                })).show();
                tr.addClass('shown');
            }
        });
    });
</script>
@endpush