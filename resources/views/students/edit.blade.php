@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center rounded-top">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-pencil-fill me-2"></i>Edit Session
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form id="editSessionForm">
                        @csrf

                        {{-- Session Title --}}
                        <div class="form-floating mb-3">
                            <input type="text" id="title" name="title" class="form-control" placeholder="Session Title" value="{{ old('title', $session->title) }}" required>
                            <label for="title"><i class="bi bi-journal-text me-2"></i>Session Title</label>
                            <small id="titleError" class="text-danger"></small>
                        </div>

                        {{-- Session Type --}}
                        <div class="form-floating mb-3">
                            <select id="session_type" name="session_type" class="form-select" required>
                                <option value="study" {{ $session->session_type == 'study' ? 'selected' : '' }}>Study</option>
                                <option value="exam" {{ $session->session_type == 'exam' ? 'selected' : '' }}>Exam</option>
                                <option value="extended_exam" {{ $session->session_type == 'extended_exam' ? 'selected' : '' }}>Extended Exam</option>
                            </select>
                            <label for="session_type"><i class="bi bi-ui-checks me-2"></i>Session Type</label>
                            <small id="session_typeError" class="text-danger"></small>
                        </div>

                        {{-- Price Per Slot --}}
                        <div class="form-floating mb-3">
                            <input type="number" id="price_per_slot" name="price_per_slot" class="form-control" placeholder="Price Per Slot" value="{{ old('price_per_slot', $session->price_per_slot) }}" step="0.01" required>
                            <label for="price_per_slot"><i class="bi bi-currency-dollar me-2"></i>Price Per Slot</label>
                            <small id="price_per_slotError" class="text-danger"></small>
                        </div>

                        {{-- Max Capacity --}}
                        <div class="form-floating mb-3">
                            <input type="number" id="max_capacity" name="max_capacity" class="form-control" placeholder="Max Capacity" value="{{ old('max_capacity', $session->max_capacity) }}" required>
                            <label for="max_capacity"><i class="bi bi-people-fill me-2"></i>Max Capacity</label>
                            <small id="max_capacityError" class="text-danger"></small>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('sessions') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left-circle"></i> Cancel
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
{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $(document).ready(function () {
        $('#editSessionForm').on('submit', function (e) {
            e.preventDefault();

            // Clear error messages
            $('#titleError, #session_typeError, #price_per_slotError, #max_capacityError').text('');

            let formData = new FormData(this);
            formData.append('_method', 'POST');

            $.ajax({
                url: '{{ route('sessions.update', $session->id) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    Swal.fire({
                        title: 'Updated!',
                        text: 'Session updated successfully.',
                        icon: 'success',
                        confirmButtonText: 'Go to Sessions'
                    }).then(result => {
                        if (result.isConfirmed) {
                            window.location.href = '{{ route('sessions') }}';
                        }
                    });
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        if (errors.title) $('#titleError').text(errors.title[0]);
                        if (errors.session_type) $('#session_typeError').text(errors.session_type[0]);
                        if (errors.price_per_slot) $('#price_per_slotError').text(errors.price_per_slot[0]);
                        if (errors.max_capacity) $('#max_capacityError').text(errors.max_capacity[0]);
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong while updating the session.',
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
