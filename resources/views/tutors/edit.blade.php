@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Create New Tutor Profile</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('tutors.update') }}" id="createTutorForm">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Tutor Name</label>
                                <input type="text" id="name" name="name" class="form-control"
                                    value="{{ old('name') }}" required>
                                <span id="nameError" class="text-danger"></span>
                            </div>


                            <div class="form-group mb-3">
                                <label for="designation" class="form-label">Designation</label>
                                <input type="text" id="designation" name="designation" class="form-control"
                                    value="{{ old('designation') }}" required>
                                <span id="designationError" class="text-danger"></span>
                            </div>

                        
                            <div class="form-group mb-3">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea id="bio" name="bio" class="form-control" rows="3" required>{{ old('bio') }}</textarea>
                                <span id="bioError" class="text-danger"></span>
                            </div>

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

    <script>
        $(document).ready(function() {
            $('#createTutorForm').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission

                // Clear previous errors
                $('#nameError').text('');
                $('#designationError').text('');
                $('#specializationError').text('');
                $('#bioError').text('');

                // Gather form data
                var formData = new FormData(this);

                // Send AJAX request
                $.ajax({
                    url: '{{ route('tutors.update') }}',
                    type: 'POST',
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
                                // Redirect to tutor listing page
                                window.location.href = '{{ route('tutors') }}';
                            }
                        });
                    },
                    error: function(xhr) {
                        // Handle validation errors
                        var errors = xhr.responseJSON.errors;
                        if (errors.name) {
                            $('#nameError').text(errors.name[0]);
                        }
                        if (errors.designation) {
                            $('#designationError').text(errors.designation[0]);
                        }
                        if (errors.specialization) {
                            $('#specializationError').text(errors.specialization[0]);
                        }
                        if (errors.bio) {
                            $('#bioError').text(errors.bio[0]);
                        }
                    }
                });
            });
        });
    </script>
@endsection
