@extends('layouts.app')

@push('styles')
    @include('partials.datatables_scripts')
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

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-body">
            <table id="practiceTestTable" class="table table-striped table-bordered display responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Parent Name</th>
                        <th>Parent Phone</th>
                        <th>Parent Email</th>
                        <th>Student Name</th>
                        <th>Student Email</th>
                        <th>School</th>
                        <th>Total Amount</th>
                        <th>Packages</th>
                        <th>Exam Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($praticetests as $praticetest)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-capitalize">{{ $praticetest->parent_first_name . ' ' . $praticetest->parent_last_name }}</td>
                            <td>
                                <a href="tel:{{ $praticetest->parent_phone }}" class="text-decoration-none text-primary">{{ $praticetest->parent_phone }}</a>
                            </td>
                            <td>
                                <a href="mailto:{{ $praticetest->parent_email }}" class="text-decoration-none text-primary">{{ $praticetest->parent_email }}</a>
                            </td>
                            <td class="text-capitalize">{{ $praticetest->student_first_name . ' ' . $praticetest->student_last_name }}</td>
                            <td>
                                @if($praticetest->student_email)
                                    <a href="mailto:{{ $praticetest->student_email }}" class="text-decoration-none text-primary">{{ $praticetest->student_email }}</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $praticetest->school }}</td>
                            <td class="fw-semibold">${{ number_format($praticetest->subtotal, 2) }}</td>
                            <td>
                                @php
                                    $dates = json_decode($praticetest->date, true) ?? [];
                                @endphp
                                @forelse ($dates as $index => $date)
                                    @php
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
                            <td>
                                @forelse (json_decode($praticetest->date, true) ?? [] as $key => $dates)
                                    @forelse ($dates as $index => $date)
                                        @php
                                            // Remove ordinal suffixes (st, nd, rd, th) and extra text
                                            $dateWithoutSuffix = preg_replace('/(\d+)(st|nd|rd|th)/', '$1', $date);
                                            // Remove everything after '@' (we only want the date and time)
                                            $dateWithoutExtraText = preg_replace('/ @.*$/', '', $dateWithoutSuffix);
                                            // Convert the cleaned date to Carbon instance, handle parsing errors
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
                                @empty
                                    N/A
                                @endforelse
                            </td>
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

<script>
    $(document).ready(function() {
        $('#practiceTestTable').DataTable({
            responsive: true,
            pageLength: 25,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excel', 'pdf', 'print'
            ],
            language: {
                search: "Search:",
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
            }
        });
    });
</script>
@endsection
