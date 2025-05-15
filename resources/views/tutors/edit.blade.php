@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Edit Tutor Profile</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('tutors.update', $tutor->id) }}" id="editTutorForm" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Tutor Name</label>
                                <input type="text" id="name" name="name" class="form-control"
                                       value="{{ old('name', $tutor->name) }}" required>
                                <span id="nameError" class="text-danger"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label for="designation" class="form-label">Designation</label>
                                <input type="text" id="designation" name="designation" class="form-control"
                                       value="{{ old('designation', $tutor->designation) }}" required>
                                <span id="designationError" class="text-danger"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea id="bio" name="bio" class="form-control" rows="3" required>{{ old('bio', $tutor->bio) }}</textarea>
                                <span id="bioError" class="text-danger"></span>
                            </div>

                            <!-- <div class="form-group mb-3">
                                <label for="image" class="form-label">Profile Image</label>
                                <input type="file" id="image" name="image" class="form-control" accept="image/*">
                                @if ($tutor->image)
                                    <img src="{{ asset('storage/' . $tutor->image) }}" alt="Current Image" class="img-thumbnail mt-2" style="max-width: 100px;">
                                @endif
                                <span id="imageError" class="text-danger"></span>
                            </div> -->

                            <div class="d-flex justify-content-between align-items-center">
                                <button type="submit" class="btn btn-primary">Update</button>
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

    <script>
        $(document).ready(function() {
            $('#editTutorForm').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                // Clear previous errors
                $('#nameError').text('');
                $('#designationError').text('');
                $('#bioError').text('');
                // $('#imageError').text('');

                // Gather form data
                var formData = new FormData(this);

                // Send AJAX request
                $.ajax({
                    url: '{{ route('tutors.update', $tutor->id) }}',
                    type: 'POST', // Laravel handles method spoofing with _method=PUT
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Tutor profile updated successfully.',
                            icon: 'success',
                            confirmButtonText: 'Go to List'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '{{ route('tutors') }}';
                            }
                        });
                    },
                    error: function(xhr) {
                        // Handle validation or server errors
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            if (errors.name) $('#nameError').text(errors.name[0]);
                            if (errors.designation) $('#designationError').text(errors.designation[0]);
                            if (errors.bio) $('#bioError').text(errors.bio[0]);
                            // if (errors.image) $('#imageError').text(errors.image[0]);
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