@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center rounded-top">
                <h4 class="mb-0 text-white">
    <i class="bi bi-person-plus-fill me-2"></i>Create New User
</h4>
                </div>
                <div class="card-body p-4">
                    <form id="createUserForm" enctype="multipart/form-data">
                        @csrf

                     
                        {{-- Name --}}
                        <div class="form-floating mb-3">
                            <input type="text" id="username" name="username" class="form-control" placeholder="Name" required>
                            <label for="username"><i class="bi bi-person-fill me-2"></i>Name</label>
                            <small id="nameError" class="text-danger"></small>
                        </div>

                        {{-- Email --}}
                        <div class="form-floating mb-3">
                            <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
                            <label for="email">
                                <i class="bi bi-envelope-fill me-2"></i>Email
                                <i class="bi bi-question-circle ms-1" data-bs-toggle="tooltip" title="We'll never share your email."></i>
                            </label>
                            <small id="emailError" class="text-danger"></small>
                        </div>

                        {{-- Password --}}
                        <div class="form-floating mb-3">
                            <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                            <label for="password"><i class="bi bi-lock-fill me-2"></i>Password</label>
                            <div id="passwordStrength" class="form-text ms-1"></div>
                            <small id="passwordError" class="text-danger"></small>
                        </div>

                        {{-- Confirm Password --}}
                        <div class="form-floating mb-3">
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                            <label for="password_confirmation"><i class="bi bi-lock-fill me-2"></i>Confirm Password</label>
                            <small id="passwordConfirmationError" class="text-danger"></small>
                        </div>

                        {{-- Role --}}
                        <div class="form-floating mb-4">
                            <select id="role" name="role" class="form-select" required>
                                <option value="" disabled selected>Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="tutor">Tutor</option>
                                <option value="parent">Parent</option>
                            </select>
                            <label for="role"><i class="bi bi-person-badge-fill me-2"></i>Role</label>
                            <small id="roleError" class="text-danger"></small>
                        </div>

                        {{-- Role-Specific Fields --}}
                        <div class="form-floating mb-3 role-dependent role-tutor" style="display: none;">
                            <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject Expertise">
                            <label for="subject"><i class="bi bi-journal-code me-2"></i>Subject Expertise</label>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('users') }}" class="btn btn-outline-secondary">
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

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<script>
    // CSRF setup
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // Tooltip init
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    $(document).ready(function () {
        // Avatar preview
        $('#avatarInput').on('change', function () {
            const [file] = this.files;
            if (file) {
                $('#avatarPreview').attr('src', URL.createObjectURL(file)).show();
            }
        });

        // Password strength check
        $('#password').on('input', function () {
            const length = $(this).val().length;
            let strength = '';
            if (length < 6) strength = 'Weak';
            else if (length < 10) strength = 'Medium';
            else strength = 'Strong';
            $('#passwordStrength').text(`Password strength: ${strength}`);
        });

        // Role based fields
        $('#role').on('change', function () {
            $('.role-dependent').hide();
            const selected = $(this).val();
            $(`.role-${selected}`).show();
        });

        // Email validation
        $('#email').on('input', function () {
            const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test($(this).val());
            $('#emailError').text(valid ? '' : 'Enter a valid email');
        });

        // Form submission
        $('#createUserForm').on('submit', function (e) {
            e.preventDefault();
            $('#nameError, #emailError, #passwordError, #passwordConfirmationError, #roleError').text('');
            const formData = new FormData(this);

            $.ajax({
                url: '{{ route('users.store') }}',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'Go to List',
                        }).then(result => {
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
