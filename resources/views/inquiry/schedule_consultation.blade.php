@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="mb-4 text-center">
        <h2 class="fw-bold mb-2" style="color: #566a7f; letter-spacing: -0.5px;">Schedule Consultation</h2>
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
            <table id="scheduleTable" class="table table-striped table-bordered display responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Primary Interest</th>
                        <th>Fees</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($schedules as $schedule)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-capitalize">{{ $schedule->name }}</td>
                            <td>
                                <a href="tel:{{ $schedule->phone_no }}" class="text-decoration-none text-primary">{{ $schedule->phone_no }}</a>
                            </td>
                            <td>
                                <a href="mailto:{{ $schedule->email }}" class="text-decoration-none text-primary">{{ $schedule->email }}</a>
                            </td>
                            <td>{{ $schedule->date->format('Y-m-d') }}</td>
                            <td>{{ $schedule->time_slot }}</td>
                            <td class="text-capitalize">{{ $schedule->primary_interest }}</td>
                            <td>{{ number_format($schedule->fees, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="No Data" width="80" class="mb-3 opacity-50">
                                    <p class="mb-0">No consultation schedules found.</p>
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
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#scheduleTable').DataTable({
                order: [[0, 'asc']],
                columnDefs: [
                    { className: 'fw-semibold', targets: [7] }, // Bold fees column
                    { className: 'text-center', targets: [6] }  // Center primary interest
                ],
                responsive: true
            });
        });
    </script>
@endpush
@endsection