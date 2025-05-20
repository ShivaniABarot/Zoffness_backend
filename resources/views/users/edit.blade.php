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
                        <!-- <input type="hidden" name="_method" value="PUT"> -->

                        {{-- Username --}}
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}" placeholder="Name" required>
                            <label for="username"><i class="bi bi-person-fill me-2"></i>Name</label>
                        </div>

                        {{-- Email --}}
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" placeholder="Email" required>
                            <label for="email">
                                <i class="bi bi-envelope-fill me-2"></i>Email
                                <i class="bi bi-question-circle ms-1" data-bs-toggle="tooltip" title="We'll never share your email."></i>
                            </label>
                        </div>

                        {{-- Password --}}
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="password" name="password" placeholder="New Password">
                            <label for="password"><i class="bi bi-lock-fill me-2"></i>New Password</label>
                            <div id="passwordStrength" class="form-text ms-1"></div>
                        </div>

                        {{-- Confirm Password --}}
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
                            <label for="password_confirmation"><i class="bi bi-lock-fill me-2"></i>Confirm Password</label>
                        </div>

                        {{-- Role --}}
                        <div class="form-floating mb-4">
                            <select class="form-select" id="role" name="role" required>
                                <option value="" disabled>Select Role</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="tutor" {{ $user->role == 'tutor' ? 'selected' : '' }}>Tutor</option>
                                <option value="parent" {{ $user->role == 'parent' ? 'selected' : '' }}>Parent</option>
                            </select>
                            <label for="role"><i class="bi bi-person-badge-fill me-2"></i>Role</label>
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
        // Tooltip init
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(el => new bootstrap.Tooltip(el));

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

            const formData = $(this).serializeArray();
            formData.push({ name: '_token', value: $('meta[name="csrf-token"]').attr('content') });
            // formData.push({ name: '_method', value: 'PUT' });

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
                        Swal.fire({
                            title: 'Validation Error!',
                            text: 'Please check the form fields.',
                            icon: 'warning'
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong.',
                            icon: 'error'
                        });
                    }
                }
            });
        });
    });
</script>
@endsection
