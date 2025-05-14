@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">College Admission Counseling</h1>

    <table class="table table-bordered" id="collegeTable">
        <thead class="custom-header">
            <tr>
                <th>#</th>
                <th>Parent Name</th>
                <th>Parent Phone</th>
                <th>Parent Email</th>
                <th>Student Name</th>
                <th>Student Email</th>
                <th>School</th>
                <th>Package</th>
                <th>Total Amount</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    $('#collegeTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('college.admission.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { 
                data: null, 
                name: 'parent_name',
                render: data => `${data.parent_first_name} ${data.parent_last_name}`
            },
            { 
                data: 'parent_phone',
                render: data => `<a href="tel:${data}">${data}</a>`
            },
            { 
                data: 'parent_email',
                render: data => `<a href="mailto:${data}">${data}</a>`
            },
            { 
                data: null, 
                name: 'student_name',
                render: data => `${data.student_first_name} ${data.student_last_name}`
            },
            { 
                data: 'student_email',
                render: data => data ? `<a href="mailto:${data}">${data}</a>` : 'N/A'
            },
            { data: 'school' },
            { data: 'packages' },
            { 
                data: 'subtotal',
                render: data => `$${parseFloat(data).toFixed(2)}`
            },
        ]
    });
});
</script>
@endpush
