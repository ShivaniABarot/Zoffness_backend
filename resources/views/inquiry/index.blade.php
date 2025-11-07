@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">All Inquiries</h5>

            </div>
            <div class="card-body">
                {{-- Filter Form --}}
                <form method="GET" action="{{ route('inquiry.index') }}" class="mb-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-4 col-sm-6">
                            <select name="booking_type" class="form-select" onchange="this.form.submit()">
                                <option value="" {{ $bookingType == '' ? 'selected' : '' }}>All Booking Types</option>
                                @foreach($bookingTypes as $type)
                                    <option value="{{ $type }}" {{ $bookingType == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

        <div class="col-md-auto col-sm-6">
            <a href="{{ route('inquiry.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-reset me-1"></i> Clear Filter
            </a>
        </div>
    </div>
</form>



                {{-- Table --}}
                <div class="table-responsive">
                    <table id="inquiryTable" class="table table-striped table-hover datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Booking Form</th>
                                <th>Parent Name</th>
                                <th>Student Name</th>
                                <th>Parent Email & Phone</th>
                                <th>Package Name</th>
                                <th>Test Date</th>
                                <th>Payment Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($allBookings as $index => $item)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>

                                                        {{-- Booking Type --}}
                                                        <td><span class="badge bg-primary">{{ $item->display_booking_type }}</span></td>

                                                        {{-- Parent Name --}}
                                                        <td>
                                                            <a href="{{ route('inquiry.show', [
                                    'type' => \Illuminate\Support\Str::slug($item->display_booking_type, '-'),
                                    'id' => $item->id
                                ]) }}">
                                                                {{ trim(
                                    ($item->parent_first_name ?? $item->parent_firstname ?? '') . ' ' .
                                    ($item->parent_last_name ?? $item->parent_lastname ?? '')
                                ) }}
                                                            </a>
                                                        </td>

                                                        {{-- Student Name --}}
                                                        <td>
                                                            {{ trim(
                                    ($item->student_first_name ?? $item->student_firstname ?? '') . ' ' .
                                    ($item->student_last_name ?? $item->studentlastname ?? '')
                                ) }}
                                                        </td>

                                                        {{-- Email & Phone --}}
                                                        <td>
                                                            {{ $item->parent_email ?? $item->email ?? '' }}<br>
                                                            {{ $item->parent_phone ?? '' }}
                                                        </td>


                                                        {{-- Package Info --}}
                                                       {{-- Package Info --}}
<!-- Package Info -->
<!-- Package Info -->
<td>
    @if(in_array($item->display_booking_type, ['College Essay', 'Executive Coaching']))
        @if(isset($item->loaded_packages) && $item->loaded_packages->count() > 0)
            <!-- Display package names without bullets, joined by comma -->
            {{ $item->loaded_packages->pluck('name')->implode(', ') }}
        @else
            <!-- Fallback to other package-related fields or the raw packages value -->
            {{ collect([
                $item->package_name ?? null,
                $item->booking_type ?? null,
                $item->package_type ?? null,
                $item->packages ?? null,
                $item->selected_package ?? null,
                $item->test_type ?? null,
                $item->note ?? null
            ])->filter()->join(', ') ?: 'N/A' }}
        @endif
    @else
        {{ collect([
            $item->package_name ?? null,
            $item->booking_type ?? null,
            $item->package_type ?? null,
            $item->packages ?? null,
            $item->selected_package ?? null,
            $item->test_type ?? null,
            $item->note ?? null
        ])->filter()->join(', ') ?: 'N/A' }}
    @endif
</td>


                                                        {{-- Exam Date --}}
                                                        <td>
                                                            {{ $item->exam_date ? \Carbon\Carbon::parse($item->exam_date)->format('d-m-Y') : '' }}
                                                        </td>

                                                        {{-- Payment Date --}}
                                                        <td>{{ $item->created_at ? $item->created_at->format('d-m-Y') : '' }}</td>

                                                        {{-- Amount --}}
                                                        <td class="fw-semibold">
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
                                                                <span class="badge bg-secondary">Booked</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5">
                                        <div class="text-muted">
                                            <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="No Data"
                                                width="80" class="mb-3 opacity-50">
                                            <p class="mb-0">No inquiries found.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                initDataTable('inquiryTable', {
                    order: [[0, 'asc']], // Sort by Payment Date
                    columnDefs: [
                        { width: "5%", targets: [0] },
                        { width: "10%", targets: [1] },
                        { width: "15%", targets: [2] },
                        { width: "15%", targets: [3] },
                        { width: "20%", targets: [4] },
                        { width: "15%", targets: [5] },
                        { width: "10%", targets: [6, 7, 8] },
                        { width: "10%", targets: [9] },
                        { className: 'fw-semibold', targets: [8] }, // bold for amount
                        { className: 'text-center', targets: [0, 9] }, // center align #
                        { orderable: false, targets: [] } // keep all sortable
                    ]
                });
            });
        </script>
    @endpush

@endsection