@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Create New User</h4>
                    </div>
                    <div class="card-body">
                        <form id="createUserForm">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" id="username" name="username" class="form-control" value="{{ old('username') }}" required>
                                <span id="nameError" class="text-danger"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                                <span id="emailError" class="text-danger"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                                <span id="passwordError" class="text-danger"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                                <span id="passwordConfirmationError" class="text-danger"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select id="role" name="role" class="form-control" required>
                                    <option value="admin">Admin</option>
                                    <option value="tutor">Tutor</option>
                                    <option value="parent">Parent</option>
                                </select>
                                <span id="roleError" class="text-danger"></span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ route('users') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include SweetAlert from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- CSRF for AJAX -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>
        $(document).ready(function () {
            $('#createUserForm').on('submit', function (e) {
                e.preventDefault();

                // Clear previous errors
                $('#nameError, #emailError, #passwordError, #passwordConfirmationError, #roleError').text('');

                var formData = $(this).serialize();

                $.ajax({
                    url: '{{ route('users.store') }}',
                    method: 'POST',
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'Go to List',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = '{{ route('users') }}';
                                }
                            });
                        }
                    },
                    error: function (xhr) {
                        const errors = xhr.responseJSON.errors;
                        if (errors) {
                            if (errors.username) $('#nameError').text(errors.username[0]);
                            if (errors.email) $('#emailError').text(errors.email[0]);
                            if (errors.password) $('#passwordError').text(errors.password[0]);
                            if (errors.password_confirmation) $('#passwordConfirmationError').text(errors.password_confirmation[0]);
                            if (errors.role) $('#roleError').text(errors.role[0]);
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Something went wrong. Please try again later.',
                                icon: 'error'
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
