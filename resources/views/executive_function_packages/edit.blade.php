@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center rounded-top">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-pencil-square me-2"></i>Edit Executive Admissions Package
                    </h4>
                </div>

                <div class="card-body p-4">
                    <form id="editExecutivePackageForm" method="POST" action="{{ route('executive_function_packages.update', $ExecutivePackage->id) }}">
                        @csrf
                        {{-- Note: no @method('PUT') because route expects POST --}}

                        {{-- Package Name --}}
                        <div class="form-floating mb-3">
                            <input type="text" name="name" id="name" class="form-control" placeholder="Package Name"
                                value="{{ old('name', $ExecutivePackage->name) }}" required>
                            <label for="name"><i class="bi bi-card-text me-2"></i>Package Name</label>
                            <small id="nameError" class="text-danger"></small>
                        </div>

                        {{-- Price --}}
                        <div class="form-floating mb-3">
                            <input type="number" step="0.01" name="price" id="price" class="form-control" placeholder="Package Price"
                                value="{{ old('price', $ExecutivePackage->price) }}" required>
                            <label for="price"><i class="bi bi-currency-dollar me-2"></i>Package Price</label>
                            <small id="priceError" class="text-danger"></small>
                        </div>

                     
                        {{-- Status --}}
                        <div class="form-floating mb-3">
                            <select name="status" id="status" class="form-select" required>
                                <option value="active" {{ old('status', $ExecutivePackage->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $ExecutivePackage->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <label for="status"><i class="bi bi-toggle-on me-2"></i>Status</label>
                            <small id="statusError" class="text-danger"></small>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('executive_function_packages.index') }}" class="btn btn-outline-secondary">
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

{{-- Bootstrap Icons --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

{{-- SweetAlert & jQuery --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function () {
        $('#editExecutivePackageForm').on('submit', function (e) {
            e.preventDefault();

            $('#nameError, #priceError, #descriptionError, #statusError').text('');

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    Swal.fire({
                        title: 'Updated!',
                        text: 'Executive Admissions Package updated successfully.',
                        icon: 'success',
                        confirmButtonText: 'Go to List'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '{{ route("executive_function_packages.index") }}';
                        }
                    });
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        if (errors.name) $('#nameError').text(errors.name[0]);
                        if (errors.price) $('#priceError').text(errors.price[0]);
                        if (errors.status) $('#statusError').text(errors.status[0]);
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
