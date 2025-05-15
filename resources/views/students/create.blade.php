@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Create New Student Profile</h4>
                    </div>
                    <div class="card-body">
                        <form id="createStudentForm">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="parent_name" class="form-label">Parent Name</label>
                                <input type="text" name="parent_name" id="parent_name" class="form-control" value="{{ old('parent_name') }}">
                                <span id="parent_nameError" class="text-danger"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label for="parent_phone" class="form-label">Parent Phone</label>
                                <input type="text" name="parent_phone" id="parent_phone" class="form-control" value="{{ old('parent_phone') }}">
                                <span id="parent_phoneError" class="text-danger"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label for="parent_email" class="form-label">Parent Email</label>
                                <input type="email" name="parent_email" id="parent_email" class="form-control" value="{{ old('parent_email') }}">
                                <span id="parent_emailError" class="text-danger"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label for="student_name" class="form-label">Student Name</label>
                                <input type="text" name="student_name" id="student_name" class="form-control" value="{{ old('student_name') }}">
                                <span id="student_nameError" class="text-danger"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label for="student_email" class="form-label">Student Email</label>
                                <input type="email" name="student_email" id="student_email" class="form-control" value="{{ old('student_email') }}">
                                <span id="student_emailError" class="text-danger"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label for="school" class="form-label">School</label>
                                <input type="text" name="school" id="school" class="form-control" value="{{ old('school') }}">
                                <span id="schoolError" class="text-danger"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label for="bank_name" class="form-label">Bank Name</label>
                                <input type="text" name="bank_name" id="bank_name" class="form-control" value="{{ old('bank_name') }}">
                                <span id="bank_nameError" class="text-danger"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label for="account_number" class="form-label">Account Number</label>
                                <input type="text" name="account_number" id="account_number" class="form-control" value="{{ old('account_number') }}">
                                <span id="account_numberError" class="text-danger"></span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ route('student') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 and jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- CSRF Setup for AJAX -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>
        $(document).ready(function () {
            $('#createStudentForm').on('submit', function (e) {
                e.preventDefault();

                // Clear all previous errors
                $('span.text-danger').text('');

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
                            for (const key in errors) {
                                $(`#${key}Error`).text(errors[key][0]);
                            }
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
