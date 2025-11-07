@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center rounded-top">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-pencil-square me-2"></i>Edit Session
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form id="editSessionForm">
                        @csrf

                        {{-- Session Title --}}
                        <div class="form-floating mb-3">
                            <input type="text" id="title" name="title" class="form-control" value="{{ $session->title }}" placeholder="Session Title" required>
                            <label for="title"><i class="bi bi-card-text me-2"></i>Session Title</label>
                            <small id="titleError" class="text-danger"></small>
                        </div>

                        {{-- Session Date --}}
                        <div class="form-floating mb-3">
                            <input type="date" id="date" name="date" class="form-control" value="{{ $session->date }}" placeholder="Session Date" required>
                            <label for="date"><i class="bi bi-calendar-event me-2"></i>Session Date</label>
                            <small id="dateError" class="text-danger"></small>
                        </div>


                        {{-- Price Per Slot --}}
                        <div class="form-floating mb-3">
                            <input type="number" id="price_per_slot" name="price_per_slot" step="0.01" class="form-control" value="{{ $session->price_per_slot }}" placeholder="Price Per Slot" required>
                            <label for="price_per_slot"><i class="bi bi-currency-dollar me-2"></i>Price Per Slot</label>
                            <small id="price_per_slotError" class="text-danger"></small>
                        </div>

                        {{-- Status --}}
                        <div class="form-floating mb-3">
                            <select id="status" name="status" class="form-select" required>
                                <option value="active" {{ $session->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="in-active" {{ $session->status == 'in-active' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <label for="status"><i class="bi bi-toggle-on me-2"></i>Status</label>
                            <small id="statusError" class="text-danger"></small>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('sessions.index') }}" class="btn btn-outline-secondary">
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
        $('#editSessionForm').on('submit', function (e) {
            e.preventDefault();
            $('#titleError, #session_typeError, #price_per_slotError, #max_capacityError, #statusError').text('');

            Swal.fire({
                title: 'Confirm Update',
                text: "Do you want to update this session?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, update it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('sessions.update', $session->id) }}',
                        method: 'POST',
                        data: $('#editSessionForm').serialize(),
                        success: function (response) {
                            Swal.fire({
                                title: 'Updated!',
                                text: response.message || 'Session updated successfully.',
                                icon: 'success',
                                confirmButtonText: 'Go to List'
                            }).then(() => {
                                window.location.href = '{{ route('sessions.index') }}';
                            });
                        },
                        error: function (xhr) {
                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON.errors;
                                if (errors.title) $('#titleError').text(errors.title[0]);
                                if (errors.price_per_slot) $('#price_per_slotError').text(errors.price_per_slot[0]);
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
                }
            });
        });
    });
</script>
@endsection
