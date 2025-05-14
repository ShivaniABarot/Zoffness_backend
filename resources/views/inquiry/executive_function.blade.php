@extends('layouts.app')

@push('styles')
    @include('partials.datatables_scripts')
@endpush

@section('content')
<div class="container">
    <h1 class="text-center">Executive Function Coaching</h1>
    {{-- @can('create', App\Models\PraticeTest::class)
        <a href="{{ route('enroll.create') }}" class="btn btn-primary mb-3">New Enrollment</a>
    @endcan --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
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
                {{-- <th>Actions</th> --}}
            </tr>
        </thead>
        <tbody>
            @forelse($coaching as $coaching)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $coaching->parent_first_name . ' ' . $coaching->parent_last_name }}</td>
                    <td><a href="tel:{{ $coaching->parent_phone }}" class="text-decoration-none">{{ $coaching->parent_phone }}</a></td>
                    <td><a href="mailto:{{ $coaching->parent_email }}" class="text-decoration-none">{{ $coaching->parent_email }}</a></td>
                    <td>{{ $coaching->student_first_name . ' ' . $coaching->student_last_name }}</td>
                    <td>
                        @if($coaching->student_email)
                            <a href="mailto:{{ $coaching->student_email }}" class="text-decoration-none">{{ $coaching->student_email }}</a>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $coaching->package_type }}</td>                    
                    <td>${{ number_format($coaching->subtotal, 2) }}</td>
                    
                    <td>
                        {{-- Uncomment the actions as needed --}}
                        {{-- <a href="{{ route('enroll.show', $enrollment->id) }}" class="btn btn-info btn-sm" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('enroll.edit', $enrollment->id) }}" class="btn btn-warning btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="#" class="btn btn-danger btn-sm" title="Delete" onclick="deleteEnroll({{ $enrollment->id }})">
                            <i class="fas fa-trash"></i>
                        </a> --}}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">No enrollments found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

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
