@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center rounded-top">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-person-plus-fill me-2"></i>Create New Student Profile
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form id="createStudentForm">
                        @csrf

                        {{-- Parent Name --}}
                        <div class="form-floating mb-3">
                            <input type="text" id="parent_name" name="parent_name" class="form-control" placeholder="Parent Name" value="{{ old('parent_name') }}" required>
                            <label for="parent_name"><i class="bi bi-person-fill me-2"></i>Parent Name</label>
                            <small id="parent_nameError" class="text-danger"></small>
                        </div>

                        {{-- Parent Phone --}}
                        <div class="form-floating mb-3">
                            <input type="text" id="parent_phone" name="parent_phone" class="form-control" placeholder="Parent Phone" value="{{ old('parent_phone') }}" required>
                            <label for="parent_phone"><i class="bi bi-telephone-fill me-2"></i>Parent Phone</label>
                            <small id="parent_phoneError" class="text-danger"></small>
                        </div>

                        {{-- Parent Email --}}
                        <div class="form-floating mb-3">
                            <input type="email" id="parent_email" name="parent_email" class="form-control" placeholder="Parent Email" value="{{ old('parent_email') }}" required>
                            <label for="parent_email"><i class="bi bi-envelope-fill me-2"></i>Parent Email</label>
                            <small id="parent_emailError" class="text-danger"></small>
                        </div>

                        {{-- Student Name --}}
                        <div class="form-floating mb-3">
                            <input type="text" id="student_name" name="student_name" class="form-control" placeholder="Student Name" value="{{ old('student_name') }}" required>
                            <label for="student_name"><i class="bi bi-person-badge-fill me-2"></i>Student Name</label>
                            <small id="student_nameError" class="text-danger"></small>
                        </div>

                        {{-- Student Email --}}
                        <div class="form-floating mb-3">
                            <input type="email" id="student_email" name="student_email" class="form-control" placeholder="Student Email" value="{{ old('student_email') }}" required>
                            <label for="student_email"><i class="bi bi-envelope-fill me-2"></i>Student Email</label>
                            <small id="student_emailError" class="text-danger"></small>
                        </div>

                        {{-- School --}}
                        <div class="form-floating mb-3">
                            <input type="text" id="school" name="school" class="form-control" placeholder="School" value="{{ old('school') }}" required>
                            <label for="school"><i class="bi bi-building me-2"></i>School</label>
                            <small id="schoolError" class="text-danger"></small>
                        </div>

                        {{-- Bank Name --}}
                        <div class="form-floating mb-3">
                            <input type="text" id="bank_name" name="bank_name" class="form-control" placeholder="Bank Name" value="{{ old('bank_name') }}" required>
                            <label for="bank_name"><i class="bi bi-bank me-2"></i>Bank Name</label>
                            <small id="bank_nameError" class="text-danger"></small>
                        </div>

                        {{-- Account Number --}}
                        <div class="form-floating mb-3">
                            <input type="text" id="account_number" name="account_number" class="form-control" placeholder="Account Number" value="{{ old('account_number') }}" required>
                            <label for="account_number"><i class="bi bi-credit-card-2-front-fill me-2"></i>Account Number</label>
                            <small id="account_numberError" class="text-danger"></small>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('student') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save2-fill"></i> Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Required scripts --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function () {
        $('#createStudentForm').on('submit', function (e) {
            e.preventDefault();
            $('#parent_nameError, #parent_phoneError, #parent_emailError, #student_nameError, #student_emailError, #schoolError, #bank_nameError, #account_numberError').text('');

            $.ajax({
                url: '{{ route('students.store') }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'Go to List',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '{{ route('student') }}';
                            }
                        });
                    }
                },
                error: function (xhr) {
                    const errors = xhr.responseJSON.errors;
                    if (errors) {
                        if (errors.parent_name) $('#parent_nameError').text(errors.parent_name[0]);
                        if (errors.parent_phone) $('#parent_phoneError').text(errors.parent_phone[0]);
                        if (errors.parent_email) $('#parent_emailError').text(errors.parent_email[0]);
                        if (errors.student_name) $('#student_nameError').text(errors.student_name[0]);
                        if (errors.student_email) $('#student_emailError').text(errors.student_email[0]);
                        if (errors.school) $('#schoolError').text(errors.school[0]);
                        if (errors.bank_name) $('#bank_nameError').text(errors.bank_name[0]);
                        if (errors.account_number) $('#account_numberError').text(errors.account_number[0]);
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong. Please try again.',
                            icon: 'error'
                        });
                    }
                }
            });
        });
    });
</script>
@endsection
