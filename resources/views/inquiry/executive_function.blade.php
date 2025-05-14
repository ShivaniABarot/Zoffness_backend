@extends('layouts.app')

@push('styles')
    @include('partials.datatables_scripts')
@endpush

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="mb-2 text-center">
        <h2 class="fw-bold mb-1">Executive Function Coaching</h2>
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
            <table id="executiveFunctionTable" class="table table-striped table-bordered display responsive nowrap" style="width:100%">
                <thead>
                    <tr>
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
                    @forelse($coaching as $coach)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-capitalize">{{ $coach->parent_first_name . ' ' . $coach->parent_last_name }}</td>
                            <td>
                                <a href="tel:{{ $coach->parent_phone }}" class="text-decoration-none text-primary">{{ $coach->parent_phone }}</a>
                            </td>
                            <td>
                                <a href="mailto:{{ $coach->parent_email }}" class="text-decoration-none text-primary">{{ $coach->parent_email }}</a>
                            </td>
                            <td class="text-capitalize">{{ $coach->student_first_name . ' ' . $coach->student_last_name }}</td>
                            <td>
                                @if($coach->student_email)
                                    <a href="mailto:{{ $coach->student_email }}" class="text-decoration-none text-primary">{{ $coach->student_email }}</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill">
                                    {{ $coach->package_type }}
                                </span>
                            </td>
                            <td class="fw-semibold">${{ number_format($coach->subtotal, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="No Data" width="80" class="mb-3 opacity-50">
                                    <p class="mb-0">No executive function coaching records found.</p>
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
        $('#executiveFunctionTable').DataTable({
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
