@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
@endpush

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="mb-2 text-center">
        <h2 class="fw-bold mb-1">College Essays</h2>
    </div>

    {{-- Success Message --}}
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
            <table id="collegeEssaysTable" class="table table-striped table-bordered display responsive nowrap" style="width:100%" aria-describedby="collegeEssaysTable_info">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Parent Name</th>
                        <th>Parent Phone</th>
                        <th>Parent Email</th>
                        <th>Student Name</th>
                        <th>Student Email</th>
                        <th>Package</th>
                        <th>Total Sessions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($collegessays as $essay)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-capitalize">{{ ($essay->parent_first_name ?? 'N/A') . ' ' . ($essay->parent_last_name ?? '') }}</td>
                            <td>
                                <a href="tel:{{ $essay->parent_phone ?? '#' }}" class="text-decoration-none text-primary">
                                    {{ $essay->parent_phone ?? 'N/A' }}
                                </a>
                            </td>
                            <td>
                                <a href="mailto:{{ $essay->parent_email ?? '#' }}" class="text-decoration-none text-primary">
                                    {{ $essay->parent_email ?? 'N/A' }}
                                </a>
                            </td>
                            <td class="text-capitalize">{{ ($essay->student_first_name ?? 'N/A') . ' ' . ($essay->student_last_name ?? '') }}</td>
                            <td>
                                @if($essay->student_email)
                                    <a href="mailto:{{ $essay->student_email }}" class="text-decoration-none text-primary">{{ $essay->student_email }}</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill">
                                    {{ $essay->packages ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="fw-semibold">{{ isset($essay->sessions) ? $essay->sessions : '0' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="No Data" width="80" class="mb-3 opacity-50">
                                    <p class="mb-0">No college essays records found.</p>
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
            if ($('#collegeEssaysTable').length) {
                $('#collegeEssaysTable').DataTable({
                    order: [[0, 'asc']],
                    columnDefs: [
                        { className: 'fw-semibold', targets: [7] }, // Bold Total Sessions column
                        { className: 'text-center', targets: [6] }  // Center Package column
                    ],
                    responsive: true,
                    pageLength: 10, // Default to 5 rows per page
                    lengthMenu: [5, 10, 25, 50, 100], // Options for rows per page
                    dom: 'Bfrtip',
                    buttons: ['excel', 'pdf']
                });
            }
        });
    </script>
@endpush
@endsection