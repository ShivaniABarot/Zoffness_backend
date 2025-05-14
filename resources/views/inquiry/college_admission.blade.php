@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">College Admission Counseling</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle shadow-sm" id="admissionTable">
            <thead class="bg-light text-center">
                <tr>
                    <th>#</th>
                    <th>Parent Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Student Name</th>
                    <th>Student Email</th>
                    <th>School</th>
                    <th>Package</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables CSS/JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#admissionTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('collegeadmission.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'parent_name', name: 'parent_name' },
            { data: 'parent_phone', name: 'parent_phone' },
            { data: 'parent_email', name: 'parent_email' },
            { data: 'student_name', name: 'student_name' },
            { data: 'student_email', name: 'student_email' },
            { data: 'school', name: 'school' },
            { data: 'packages', name: 'packages' },
            { data: 'subtotal', name: 'subtotal' }
        ]
    });
});
</script>
@endpush
