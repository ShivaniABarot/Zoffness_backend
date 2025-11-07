@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2><i class="bx bx-money"></i> All Transactions</h2>
        </div>

        <div class="card p-3 shadow-sm">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Filter Form -->
            <form method="GET" action="{{ route('transactions.index') }}" class="row g-3 mb-4 align-items-end"
                id="filterForm">
                <div class="col-md-3">
                    <label class="form-label">Date Filter</label>
                    <select name="filter" id="filter" class="form-select">
                        <option value="today" {{ $filter == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="last_week" {{ $filter == 'last_week' ? 'selected' : '' }}>Last Week</option>
                        <option value="last_month" {{ $filter == 'last_month' ? 'selected' : '' }}>Last Month</option>
                        <option value="custom" {{ $filter == 'custom' ? 'selected' : '' }}>Custom</option>
                    </select>
                </div>

                <div class="col-md-4 custom-date-range {{ $filter == 'custom' ? '' : 'd-none' }}">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label">From</label>
                            <input type="date" name="from_date" value="{{ $from_date }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">To</label>
                            <input type="date" name="to_date" value="{{ $to_date }}" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="all" {{ $statusFilter == 'all' ? 'selected' : '' }}>All</option>
                        <option value="succeeded" {{ $statusFilter == 'succeeded' ? 'selected' : '' }}>Succeeded</option>
                        <option value="requires_payment_method" {{ $statusFilter == 'requires_payment_method' ? 'selected' : '' }}>Incomplete</option>
                        <option value="processing" {{ $statusFilter == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="canceled" {{ $statusFilter == 'canceled' ? 'selected' : '' }}>Canceled</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <button class="btn btn-primary w-100">Filter</button>
                </div>

                <div class="col-md-1">
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary w-100">Clear</a>
                </div>
                
                <div class="col-md-1">
    <button type="submit" formaction="{{ route('transactions.export') }}" formmethod="GET" class="btn btn-success w-100">
        <i class="bx bx-download"></i> Export
    </button>
</div>


            
            </form>

            <!-- Transactions Table -->
            <table id="transactionsTable" class="table table-striped table-bordered w-100">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Created Date Time</th>
                        <!-- <th>Created Time</th> -->
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $txn)
                        <tr>
                            <td>{{ $txn->id }}</td>
                            <td>
                                {{ $txn->customer_name }}
                                @if($txn->customer_email)
                                    <br><small>{{ $txn->customer_email }}</small>
                                @endif
                            </td>
                            <td>${{ number_format($txn->amount / 100, 2) }}</td>
                            <td>
                                @php
                                    $displayStatus = $txn->status;
                                    if ($txn->status === 'requires_payment_method')
                                        $displayStatus = 'Incomplete';
                                    $statusClass = match ($txn->status) {
                                        'succeeded' => 'bg-success',
                                        'requires_payment_method' => 'bg-danger',
                                        'processing' => 'bg-info',
                                        'canceled' => 'bg-secondary',
                                        default => 'bg-warning',
                                    };
                                @endphp
                                <span
                                    class="badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $displayStatus)) }}</span>
                            </td>
                            <td>{{ \Carbon\Carbon::createFromTimestamp($txn->created)->format('M d Y H:i') }}</td>
                            <!-- <td>{{ \Carbon\Carbon::createFromTimestamp($txn->created)->format('H:i') }}</td> -->
                            <td>
                                <a href="{{ route('transactions.show', $txn->id) }}" class="btn btn-sm">
                                    <i class="bx bx-show bx-md"></i>
                                </a>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No transactions found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#transactionsTable').DataTable({
                order: [[4, 'desc']], // sort by date
            });

            const filterSelect = document.getElementById('filter');
            const dateRange = document.querySelector('.custom-date-range');

            function toggleDateRange() {
                if (filterSelect.value === 'custom') {
                    dateRange.classList.remove('d-none');
                } else {
                    dateRange.classList.add('d-none');
                }
            }

            filterSelect.addEventListener('change', toggleDateRange);
            toggleDateRange();
        });
    </script>

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

        .dataTables_paginate,
        .dataTables_info {
            margin-top: 10px;
        }

        #transactionsTable {
            border-radius: 10px;
            overflow: hidden;
        }
    </style>
@endsection