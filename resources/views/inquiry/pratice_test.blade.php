@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
@endpush

@section('content')
    <div class="container py-5">
        <!-- Page Header -->
        <div class="mb-2 text-center">
            <h2 class="fw-bold mb-1">Practice Test & Analysis List</h2>
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
                <table id="practiceTestTable" class="table table-striped table-bordered display responsive nowrap"
                    style="width:100%" aria-describedby="practiceTestTable_info">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Parent Name</th>
                            <th>Parent Phone</th>
                            <th>Parent Email</th>
                            <th>Student Name</th>
                            <th>Student Email</th>
                            <th>Total Amount</th>
                            <th>Package</th>
                            <th>Exam Date & Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($praticetests as $praticetest)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-capitalize">
                                    {{ ($praticetest->parent_first_name ?? 'N/A') . ' ' . ($praticetest->parent_last_name ?? '') }}
                                </td>
                                <td>
                                    <a href="tel:{{ $praticetest->parent_phone ?? '#' }}"
                                        class="text-decoration-none text-primary">
                                        {{ $praticetest->parent_phone ?? 'N/A' }}
                                    </a>
                                </td>
                                <td>
                                    <a href="mailto:{{ $praticetest->parent_email ?? '#' }}"
                                        class="text-decoration-none text-primary">
                                        {{ $praticetest->parent_email ?? 'N/A' }}
                                    </a>
                                </td>
                                <td class="text-capitalize">
                                    {{ ($praticetest->student_first_name ?? 'N/A') . ' ' . ($praticetest->student_last_name ?? '') }}
                                </td>
                                <td>
                                    @if($praticetest->student_email)
                                        <a href="mailto:{{ $praticetest->student_email }}"
                                            class="text-decoration-none text-primary">{{ $praticetest->student_email }}</a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td class="fw-semibold">
                                    ${{ isset($praticetest->subtotal) ? number_format($praticetest->subtotal, 2) : '0.00' }}
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill"
                                        style="font-size: 0.75rem; font-weight: 500;">
                                        @if ($praticetest->packages && $praticetest->packages->count())
                                            {!! $praticetest->packages->pluck('name')->implode('<br>') . '<br>' !!}
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </td>



                                <td>
                                    @php
                                        $dates = json_decode($praticetest->date, true) ?? [];
                                    @endphp
                                    @forelse ($dates as $index => $date)
                                        @php
                                            // Remove ordinal suffixes (st, nd, rd, th) and extra text
                                            $dateWithoutSuffix = preg_replace('/(\d+)(st|nd|rd|th)/', '$1', $date);
                                            $dateWithoutExtraText = preg_replace('/ @.*$/', '', $dateWithoutSuffix);
                                            try {
                                                $formattedDate = \Carbon\Carbon::parse($dateWithoutExtraText)->format('d F, l @gA');
                                            } catch (\Exception $e) {
                                                $formattedDate = 'Invalid Date';
                                            }
                                        @endphp
                                        {{ $index + 1 }}. {{ $formattedDate }}<br>
                                    @empty
                                        N/A
                                    @endforelse
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <div class="text-muted">
                                        <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="No Data"
                                            width="80" class="mb-3 opacity-50">
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
            $(document).ready(function () {
                if ($('#practiceTestTable').length) {
                    $('#practiceTestTable').DataTable({
                        order: [[0, 'asc']],
                        columnDefs: [
                            { className: 'fw-semibold', targets: [6] }, // Bold Total Amount column
                            { className: 'text-center', targets: [7] }, // Center Package column
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