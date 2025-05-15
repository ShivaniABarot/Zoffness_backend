@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Create New Tutor Profile</h4>
                    </div>
                    <div class="card-body">
                        <form id="createTutorForm" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Tutor Name</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                                <span id="nameError" class="text-danger"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label for="designation" class="form-label">Designation</label>
                                <input type="text" id="designation" name="designation" class="form-control" value="{{ old('designation') }}" required>
                                <span id="designationError" class="text-danger"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea id="bio" name="bio" class="form-control" rows="3" required>{{ old('bio') }}</textarea>
                                <span id="bioError" class="text-danger"></span>
                            </div>

                            <!-- <div class="form-group mb-3">
                                <label for="image" class="form-label">Profile Image</label>
                                <input type="file" id="image" name="image" class="form-control" accept="image/jpeg,image/png,image/jpg" required>
                                <span id="imageError" class="text-danger"></span>
                            </div> -->

                            <div class="d-flex justify-content-between align-items-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ route('tutors') }}" class="btn btn-secondary">Cancel</a>
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
            $('#createTutorForm').on('submit', function (e) {
                e.preventDefault();

                // Clear previous errors
                $('#nameError, #designationError, #bioError, #imageError').text('');

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
                            if (errors.bio) $('#bioError').text(errors.bio[0]);
                            if (errors.image) $('#imageError').text(errors.image[0]);
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Something went wrong. Please check console or logs.',
                                icon: 'error'
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
