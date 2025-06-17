@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center rounded-top">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-person-plus-fill me-2"></i>Create New Tutor Profile
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form id="createTutorForm" enctype="multipart/form-data">
                        @csrf

                        {{-- Name --}}
                        <div class="form-floating mb-3">
                            <input type="text" id="name" name="name" class="form-control" placeholder="Tutor Name" required>
                            <label for="name"><i class="bi bi-person-fill me-2"></i>Tutor Name</label>
                            <small id="nameError" class="text-danger"></small>
                        </div>

                        {{-- Designation --}}
                        <div class="form-floating mb-3">
                            <input type="text" id="designation" name="designation" class="form-control" placeholder="Designation" required>
                            <label for="designation"><i class="bi bi-award-fill me-2"></i>Designation</label>
                            <small id="designationError" class="text-danger"></small>
                        </div>

                        {{-- Email --}}
                        <div class="form-floating mb-3">
                            <input type="email" id="email" name="email" class="form-control" placeholder="Email Address" required>
                            <label for="email"><i class="bi bi-envelope-fill me-2"></i>Email Address</label>
                            <small id="emailError" class="text-danger"></small>
                        </div>

                        {{-- Bio --}}
                        <div class="mb-3">
                            <label for="bio" class="form-label"><i class="bi bi-card-text me-2"></i>Bio</label>
                            <textarea id="bio" name="bio" class="form-control" rows="4" placeholder="Write a short bio..." required></textarea>
                            <small id="bioError" class="text-danger"></small>
                        </div>

                        {{-- Profile Image (optional, currently hidden) --}}
                        <!--
                        <div class="mb-3">
                            <label for="image" class="form-label"><i class="bi bi-image-fill me-2"></i>Profile Image</label>
                            <input type="file" id="image" name="image" class="form-control" accept="image/*">
                            <small id="imageError" class="text-danger"></small>
                        </div>
                        -->

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('tutors') }}" class="btn btn-outline-secondary">
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

{{-- Scripts --}}
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
        $('#createTutorForm').on('submit', function (e) {
            e.preventDefault();
            $('#nameError, #designationError, #emailError, #bioError, #imageError').text('');
            var formData = new FormData(this);

            $.ajax({
                url: '{{ route('tutors.store') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'Go to List',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '{{ route('tutors') }}';
                            }
                        });
                    }
                },
                error: function (xhr) {
                    const errors = xhr.responseJSON.errors;
                    if (errors) {
                        if (errors.name) $('#nameError').text(errors.name[0]);
                        if (errors.designation) $('#designationError').text(errors.designation[0]);
                        if (errors.email) $('#emailError').text(errors.email[0]);
                        if (errors.bio) $('#bioError').text(errors.bio[0]);
                        if (errors.image) $('#imageError').text(errors.image[0]);
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
