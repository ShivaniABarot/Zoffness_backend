@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center rounded-top">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-journal-plus me-2"></i>Create SAT/ACT Package
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form id="createPackageForm">
                        @csrf

                        {{-- Name --}}
                        <div class="form-floating mb-3">
                            <input type="text" id="name" name="name" class="form-control" placeholder="Package Name" required>
                            <label for="name"><i class="bi bi-card-text me-2"></i>Package Name</label>
                            <small id="nameError" class="text-danger"></small>
                        </div>

                        {{-- Price --}}
                        <div class="form-floating mb-3">
                            <input type="number" id="price" name="price" class="form-control" placeholder="Price" required>
                            <label for="price"><i class="bi bi-currency-dollar me-2"></i>Price</label>
                            <small id="priceError" class="text-danger"></small>
                        </div>

                        {{-- Description --}}
                        <div class="form-floating mb-3">
                            <textarea id="description" name="description" class="form-control" placeholder="Description" style="height: 100px"></textarea>
                            <label for="description"><i class="bi bi-info-circle-fill me-2"></i>Description</label>
                            <small id="descriptionError" class="text-danger"></small>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('satact_course.index') }}" class="btn btn-outline-secondary">
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
        $('#createPackageForm').on('submit', function (e) {
            e.preventDefault();

            $('#nameError, #priceError, #descriptionError').text('');

            $.ajax({
                url: '{{ route('satact_course.store') }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Package created successfully.',
                        icon: 'success',
                        confirmButtonText: 'Go to List'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '{{ route('satact_course.index') }}';
                        }
                    });
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        if (errors.name) $('#nameError').text(errors.name[0]);
                        if (errors.price) $('#priceError').text(errors.price[0]);
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
