@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center rounded-top">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-pencil-square me-2"></i>Edit Tutor Profile
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form id="editTutorForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Tutor Name --}}
                        <div class="form-floating mb-3">
                            <input type="text" id="name" name="name" class="form-control" placeholder="Tutor Name" value="{{ old('name', $tutor->name) }}" required>
                            <label for="name"><i class="bi bi-person-fill me-2"></i>Tutor Name</label>
                            <small id="nameError" class="text-danger"></small>
                        </div>

                        {{-- Designation --}}
                        <div class="form-floating mb-3">
                            <input type="text" id="designation" name="designation" class="form-control" placeholder="Designation" value="{{ old('designation', $tutor->designation) }}" required>
                            <label for="designation"><i class="bi bi-award-fill me-2"></i>Designation</label>
                            <small id="designationError" class="text-danger"></small>
                        </div>

                        {{-- Bio --}}
                        <div class="mb-3">
                            <label for="bio" class="form-label"><i class="bi bi-card-text me-2"></i>Bio</label>
                            <textarea id="bio" name="bio" class="form-control" rows="4" placeholder="Write a short bio..." required>{{ old('bio', $tutor->bio) }}</textarea>
                            <small id="bioError" class="text-danger"></small>
                        </div>

                        {{-- Image upload (optional) --}}
                        <!--
                        <div class="mb-3">
                            <label for="image" class="form-label"><i class="bi bi-image-fill me-2"></i>Profile Image</label>
                            <input type="file" id="image" name="image" class="form-control" accept="image/*">
                            @if ($tutor->image)
                                <img src="{{ asset('storage/' . $tutor->image) }}" alt="Current Image" class="img-thumbnail mt-2" style="max-width: 100px;">
                            @endif
                            <small id="imageError" class="text-danger"></small>
                        </div>
                        -->

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('tutors') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save2-fill"></i> Update
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
        $('#editTutorForm').on('submit', function (e) {
            e.preventDefault();

            // Clear previous errors
            $('#nameError, #designationError, #bioError').text('');

            let formData = new FormData(this);
            formData.append('_method', 'PUT');

            $.ajax({
                url: '{{ route('tutors.update', $tutor->id) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Tutor profile updated successfully.',
                        icon: 'success',
                        confirmButtonText: 'Go to List',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '{{ route('tutors') }}';
                        }
                    });
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        if (errors.name) $('#nameError').text(errors.name[0]);
                        if (errors.designation) $('#designationError').text(errors.designation[0]);
                        if (errors.bio) $('#bioError').text(errors.bio[0]);
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while updating the tutor profile.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        });
    });
</script>
@endsection
