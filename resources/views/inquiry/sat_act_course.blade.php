@extends('layouts.app')

@push('styles')
    @include('partials.datatables_scripts')
@endpush

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="mb-2 text-center">
        <h2 class="fw-bold mb-1">SAT/ACT Course List</h2>
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
            <table id="satActCourseTable" class="table table-striped table-bordered display responsive nowrap" style="width:100%">
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
                        <th>Exam Date & Time</th>
                        <th>Packages</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sat_act_course as $course)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-capitalize">{{ $course->parent_firstname . ' ' . $course->parent_lastname }}</td>
                            <td>
                                <a href="tel:{{ $course->parent_phone }}" class="text-decoration-none text-primary">{{ $course->parent_phone }}</a>
                            </td>
                            <td>
                                <a href="mailto:{{ $course->parent_email }}" class="text-decoration-none text-primary">{{ $course->parent_email }}</a>
                            </td>
                            <td class="text-capitalize">{{ $course->student_firstname . ' ' . $course->student_lastname }}</td>
                            <td>
                                @if($course->student_email)
                                    <a href="mailto:{{ $course->student_email }}" class="text-decoration-none text-primary">{{ $course->student_email }}</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $course->school }}</td>
                            <td class="fw-semibold">${{ number_format($course->amount, 2) }}</td>
                            <td>
                                @php
                                    try {
                                        $formattedDate = \Carbon\Carbon::parse($course->created_at)->format('d F, l @gA');
                                    } catch (\Exception $e) {
                                        $formattedDate = 'Invalid Date';
                                    }
                                @endphp
                                {{ $formattedDate }}
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill">
                                    {{ $course->package_name }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <div class="text-muted">
                                    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="No Data" width="80" class="mb-3 opacity-50">
                                    <p class="mb-0">No SAT/ACT courses found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @can('create', App\Models\SAT_ACT_Course::class)
        <div class="mt-3 text-end">
            <a href="{{ route('sat_act_course.create') }}" class="btn btn-primary">Add New Course</a>
        </div>
    @endcan
</div>

<script>
    $(document).ready(function() {
        $('#satActCourseTable').DataTable({
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