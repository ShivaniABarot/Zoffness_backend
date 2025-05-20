@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center rounded-top">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-pencil-square me-2"></i>Edit Package
                    </h4>
                </div>

                <div class="card-body p-4">
                    <form id="editPackageForm">
                        @csrf
                        @method('PUT')

                        {{-- Hidden ID --}}
                        <input type="hidden" id="package_id" value="{{ $package->id }}">

                        {{-- Package Name --}}
                        <div class="form-floating mb-3">
                            <input type="text" name="name" id="name" class="form-control" placeholder="Package Name"
                                value="{{ old('name', $package->name) }}" required>
                            <label for="name"><i class="bi bi-card-text me-2"></i>Package Name</label>
                            <small id="nameError" class="text-danger"></small>
                        </div>

                        {{-- Price --}}
                        <div class="form-floating mb-3">
                            <input type="number" step="0.01" name="price" id="price" class="form-control" placeholder="Package Price"
                                value="{{ old('price', $package->price) }}" required>
                            <label for="price"><i class="bi bi-currency-dollar me-2"></i>Package Price</label>
                            <small id="priceError" class="text-danger"></small>
                        </div>

                        {{-- Number of Sessions --}}
                        <div class="form-floating mb-3">
                            <input type="number" name="number_of_sessions" id="number_of_sessions" class="form-control" placeholder="Number of Sessions"
                                value="{{ old('number_of_sessions', $package->number_of_sessions) }}" required>
                            <label for="number_of_sessions"><i class="bi bi-123 me-2"></i>Number of Sessions</label>
                            <small id="numberOfSessionsError" class="text-danger"></small>
                        </div>

                        {{-- Description --}}
                        <div class="form-floating mb-3">
                            <textarea name="description" id="description" class="form-control" placeholder="Enter description..." style="height: 100px">{{ old('description', $package->description) }}</textarea>
                            <label for="description"><i class="bi bi-info-circle-fill me-2"></i>Description</label>
                            <small id="descriptionError" class="text-danger"></small>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('packages.index') }}" class="btn btn-outline-secondary">
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
        $('#editPackageForm').on('submit', function (e) {
            e.preventDefault();
            
            // Clear previous error messages
            $('#nameError, #priceError, #numberOfSessionsError, #descriptionError').text('');

            let packageId = $('#package_id').val();

            $.ajax({
                url: '/packages/' + packageId,
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    Swal.fire({
                        title: 'Updated!',
                        text: 'Package updated successfully.',
                        icon: 'success',
                        confirmButtonText: 'Go to List'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '{{ route('packages.index') }}';
                        }
                    });
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        if (errors.name) $('#nameError').text(errors.name[0]);
                        if (errors.price) $('#priceError').text(errors.price[0]);
                        if (errors.number_of_sessions) $('#numberOfSessionsError').text(errors.number_of_sessions[0]);
                        if (errors.description) $('#descriptionError').text(errors.description[0]);
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
