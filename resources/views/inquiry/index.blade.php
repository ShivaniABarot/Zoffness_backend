@extends('layouts.app')

@section('content')
    <style>
        .inquiry-container {
            padding: 2rem 0;
        }
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .table {
            margin-bottom: 0;
        }
        .table th {
            font-weight: 600;
            padding: 1rem;
            background-color: #343a40;
            color: white;
        }
        .table td {
            padding: 1rem;
            vertical-align: middle;
        }
        .table tr:hover {
            background-color: #f8f9fa;
        }
        .badge {
            padding: 0.5em 1em;
            font-size: 0.9em;
            border-radius: 12px;
        }
        .form-select {
            border-radius: 6px;
            padding: 0.5rem 1rem;
        }
        .btn-outline-secondary {
            border-radius: 6px;
            padding: 0.5rem 1.5rem;
        }
        .filter-form {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }
        h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #1a202c;
        }
        @media (max-width: 768px) {
            .table th, .table td {
                padding: 0.75rem;
                font-size: 0.9rem;
            }
            h1 {
                font-size: 1.5rem;
            }
        }
    </style>

    <div class="container inquiry-container">
        <h1 class="mb-4">All Inquiries</h1>

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('inquiry.index') }}" class="filter-form">
            <div class="row g-3 align-items-center">
                <div class="col-md-4 col-sm-6">
                    <select name="booking_type" class="form-select" onchange="this.form.submit()">
                        <option value="">All Booking Types</option>
                        @foreach($bookingTypes as $type)
                            <option value="{{ $type }}" {{ $bookingType == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-auto col-sm-6">
                    <a href="{{ route('inquiry.index') }}" class="btn btn-outline-secondary">Clear Filter</a>
                </div>
            </div>
        </form>

        {{-- Combined Table --}}
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Booking Type</th>
                        <th>Parent Name</th>
                        <th>Student Name</th>
                        <th>Parent Email</th>
                        <th>Package Name</th>
                        <th>Exam Date</th>
                        <th>Payment Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($allBookings as $index => $item)
                        <tr>
                            {{-- Booking Type --}}
                            <td><span class="badge bg-primary">{{ $item->display_booking_type }}</span></td>

                            {{-- Parent Name --}}
                            <td>
                                {{ trim(
                                    ($item->parent_first_name ?? $item->parent_firstname ?? '') . ' ' .
                                    ($item->parent_last_name ?? $item->parentlastname ?? '')
                                ) }}
                            </td>

                            {{-- Student Name --}}
                            <td>
                                {{ trim(
                                    ($item->student_first_name ?? $item->student_firstname ?? '') . ' ' .
                                    ($item->student_last_name ?? $item->studentlastname ?? '')
                                ) }}
                            </td>

                            {{-- Email --}}
                            <td>{{ $item->student_email ?? $item->email ?? 'N/A' }}</td>

                            {{-- Package Info --}}
                            <td>
                                {{ collect([
                                    $item->package_name ?? null,
                                    $item->booking_type ?? null,
                                    $item->package_type ?? null,
                                    $item->packages ?? null,
                                    $item->test_type ?? null,
                                    $item->note ?? null
                                ])->filter()->join(', ') ?: 'N/A' }}
                            </td>

                            {{-- Exam Date --}}
                            <td>
                                {{ $item->exam_date ? \Carbon\Carbon::parse($item->exam_date)->format('d-m-Y') : '' }}
                            </td>

                            {{-- Created At full --}}
                            <td>{{ $item->created_at ? $item->created_at->format('d-m-Y') : '' }}</td>

                            {{-- Amount --}}
                            <td>
                                @php
                                    $amountValue = $item->amount
                                        ?? $item->payment_amount
                                        ?? $item->subtotal
                                        ?? $item->sessions;
                                @endphp
                                {{ is_numeric($amountValue) ? number_format($amountValue, 2) : ($amountValue ?? 'N/A') }}
                            </td>

                            {{-- Status --}}
                            <td>
                                @if(!empty($item->status))
                                    @php
                                        $statusClass = match (strtolower($item->status)) {
                                            'paid' => 'success',
                                            'pending' => 'warning',
                                            'cancelled', 'failed' => 'danger',
                                            default => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}">{{ ucfirst($item->status) }}</span>
                                @else
                                    <span class="badge bg-secondary">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No inquiries found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
