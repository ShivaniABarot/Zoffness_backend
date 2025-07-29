@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
@endpush

@section('content')
<div class="container py-5">
    <div class="mb-4 text-center">
        <h2 class="fw-bold mb-2" style="color: #566a7f; letter-spacing: -0.5px;">Online Payments</h2>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden" style="background: #fff; transition: all 0.3s ease;">
        <div class="card-body pt-0">
            <table id="paymentTable" class="table table-striped table-bordered display responsive nowrap" style="width:100%" aria-describedby="paymentTable_info">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Parent Name</th>
                        <th>Parent Email</th>
                        <th>Parent Phone</th>
                        <th>Student Name</th>
                        <th>Payment Type</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $payment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-capitalize">{{ $payment->user ? $payment->user->getFilamentName() : 'N/A' }}</td>
                            <td>
                                <a href="mailto:{{ $payment->user ? $payment->user->email : '#' }}" class="text-decoration-none text-primary">
                                    {{ $payment->user ? $payment->user->email : 'N/A' }}
                                </a>
                            </td>
                            <td>
                                <a href="tel:{{ $payment->user ? $payment->user->phone_no : '#' }}" class="text-decoration-none text-primary">
                                    {{ $payment->user ? $payment->user->phone_no : 'N/A' }}
                                </a>
                            </td>
                            <td class="text-capitalize">{{ $payment->student_first_name . ' ' . $payment->student_last_name }}</td>
                            <td class="text-capitalize">{{ $payment->payment_type }}</td>
                            <td>{{ number_format($payment->payment_amount, 2) }}</td>
                            <td>{{ $payment->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="No Data" width="80" class="mb-3 opacity-50">
                                    <p class="mb-0">No payment records found.</p>
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
            $('#paymentTable').DataTable({
                order: [[0, 'asc']],
                columnDefs: [
                    { className: 'fw-semibold', targets: [6] },
                    { className: 'text-center', targets: [5] }
                ],
                responsive: true,
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                dom: 'Bfrtip',
                // buttons: ['excel', 'pdf']
            });
        });
    </script>
@endpush
@endsection