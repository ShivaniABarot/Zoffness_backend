@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center rounded-top">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-pencil-square me-2"></i>Edit User
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form id="editUserForm">
                        @csrf

                        {{-- First Name --}}
                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="firstname" name="firstname" value="{{ $user->firstname }}" placeholder="First Name" required>
                                <label for="firstname"><i class="bi bi-person-fill me-2"></i>First Name</label>
                            </div>
                            <small id="firstnameError" class="text-danger mt-1 d-block"></small>
                        </div>

                        {{-- Last Name --}}
                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="lastname" name="lastname" value="{{ $user->lastname }}" placeholder="Last Name" required>
                                <label for="lastname"><i class="bi bi-person-fill me-2"></i>Last Name</label>
                            </div>
                            <small id="lastnameError" class="text-danger mt-1 d-block"></small>
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" placeholder="Email" required>
                                <label for="email">
                                    <i class="bi bi-envelope-fill me-2"></i>Email
                                    <i class="bi bi-question-circle ms-1" data-bs-toggle="tooltip" title="We'll never share your email."></i>
                                </label>
                            </div>
                            <small id="emailError" class="text-danger mt-1 d-block"></small>
                        </div>

                        {{-- Phone Number --}}
                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="phone_no" name="phone_no" value="{{ $user->phone_no }}" placeholder="Phone Number" required>
                                <label for="phone_no"><i class="bi bi-telephone-fill me-2"></i>Phone Number</label>
                            </div>
                            <small id="phoneNoError" class="text-danger mt-1 d-block"></small>
                        </div>

                        {{-- Password --}}
                        <div class="mb-3 position-relative">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="password" name="password" placeholder="New Password">
                                <label for="password"><i class="bi bi-lock-fill me-2"></i>New Password</label>
                            </div>
                            <i class="bi bi-eye-slash position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer" id="togglePassword"></i>
                            <div id="passwordStrength" class="form-text ms-1"></div>
                            <small id="passwordError" class="text-danger mt-1 d-block"></small>
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-3 position-relative">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
                                <label for="password_confirmation"><i class="bi bi-lock-fill me-2"></i>Confirm Password</label>
                            </div>
                            <i class="bi bi-eye-slash position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer" id="toggleConfirmPassword"></i>
                            <small id="passwordConfirmationError" class="text-danger mt-1 d-block"></small>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('users') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save2-fill"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Dependencies --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function () {
    // Tooltip
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(el => new bootstrap.Tooltip(el));

    // Toggle password visibility
    $('#togglePassword').click(function() {
        const input = $('#password');
        const type = input.attr('type') === 'password' ? 'text' : 'password';
        input.attr('type', type);
        $(this).toggleClass('bi-eye bi-eye-slash');
    });
    $('#toggleConfirmPassword').click(function() {
        const input = $('#password_confirmation');
        const type = input.attr('type') === 'password' ? 'text' : 'password';
        input.attr('type', type);
        $(this).toggleClass('bi-eye bi-eye-slash');
    });

    // Password strength
    $('#password').on('input', function () {
        const length = $(this).val().length;
        let strength = '';
        if (length === 0) strength = '';
        else if (length < 6) strength = 'Weak';
        else if (length < 10) strength = 'Medium';
        else strength = 'Strong';
        $('#passwordStrength').text(strength ? `Password strength: ${strength}` : '');
    });

    // AJAX form submit
    $('#editUserForm').on('submit', function (e) {
        e.preventDefault();

        // Clear previous errors
        $('#firstnameError, #lastnameError, #phoneNoError, #emailError, #passwordError, #passwordConfirmationError').text('');

        // Frontend validations
        let valid = true;
        const firstname = $('#firstname').val().trim();
        const lastname = $('#lastname').val().trim();
        const email = $('#email').val().trim();
        const phone = $('#phone_no').val().trim();
        const password = $('#password').val();
        const confirmPassword = $('#password_confirmation').val();

        if (!firstname) { $('#firstnameError').text('First Name is required'); valid = false; }
        if (!lastname) { $('#lastnameError').text('Last Name is required'); valid = false; }
        if (!email) { $('#emailError').text('Email is required'); valid = false; }
        else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { $('#emailError').text('Enter a valid email'); valid = false; }
        if (!phone) { $('#phoneNoError').text('Phone Number is required'); valid = false; }
        else if (!/^\d{10}$/.test(phone)) { $('#phoneNoError').text('Phone Number must be 10 digits'); valid = false; }
        if (password && confirmPassword && password !== confirmPassword) { $('#passwordConfirmationError').text('Passwords do not match'); valid = false; }

        if (!valid) return;

        // Submit via AJAX
        const formData = $(this).serializeArray();
        formData.push({ name: '_token', value: $('meta[name="csrf-token"]').attr('content') });

        $.ajax({
            url: '{{ route('users.update', $user->id) }}',
            type: 'POST',
            data: formData,
            success: function () {
                Swal.fire({
                    title: 'Updated!',
                    text: 'User updated successfully.',
                    icon: 'success',
                    confirmButtonText: 'Go back'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('users') }}';
                    }
                });
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON?.errors;
                    if (errors) {
                        if (errors.firstname) $('#firstnameError').text(errors.firstname[0]);
                        if (errors.lastname) $('#lastnameError').text(errors.lastname[0]);
                        if (errors.email) $('#emailError').text(errors.email[0]);
                        if (errors.phone_no) $('#phoneNoError').text(errors.phone_no[0]);
                        if (errors.password) $('#passwordError').text(errors.password[0]);
                        if (errors.password_confirmation) $('#passwordConfirmationError').text(errors.password_confirmation[0]);
                    }
                } else {
                    Swal.fire({ title: 'Error!', text: 'Something went wrong.', icon: 'error' });
                }
            }
        });
    });
});
</script>
@endsection
