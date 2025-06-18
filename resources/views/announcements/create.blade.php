@extends('layouts.app')

@section('head')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        tinymce.init({
            selector: '#content',
            plugins: 'link image code lists',
            toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image | code',
            menubar: false,
            height: 300
        });

        $('#to_emails').select2({
            placeholder: 'Select student emails',
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center rounded-top">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-megaphone-fill me-2"></i>Create New Announcement
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form id="announcementForm" method="POST" action="{{ route('announcements.store') }}">
                        @csrf

                        {{-- Title --}}
                        <div class="form-floating mb-3">
                            <input type="text" id="title" name="title" class="form-control" placeholder="Announcement Title" required>
                            <label for="title"><i class="bi bi-type me-2"></i>Title</label>
                            <small id="titleError" class="text-danger"></small>
                        </div>

                        {{-- From Email (Tutor) --}}
                        <div class="mb-3">
                            <label for="from_email" class="form-label fw-bold"><i class="bi bi-person-lines-fill me-2"></i>From Email (Tutor) <span class="text-danger">*</span></label>
                            <select id="from_email" name="from_email" class="form-control" required>
                                <option value="">-- Select Tutor Email --</option>
                                @foreach($tutors as $tutor)
                                    <option value="{{ $tutor->email }}">{{ $tutor->email }}</option>
                                @endforeach
                            </select>
                            <small id="from_emailError" class="text-danger"></small>
                        </div>

                        {{-- Student Emails --}}
                        <div class="mb-3">
                            <label for="to_emails" class="form-label fw-bold"><i class="bi bi-envelope me-2"></i> To Email <span class="text-danger">*</span></label>
                            <select id="to_emails" name="to_emails[]" class="form-control" multiple="multiple" required>
                                @foreach($students as $student)
                                    <option value="{{ $student->student_email }}">{{ $student->student_email }}</option>
                                @endforeach
                            </select>
                            <small id="to_emailsError" class="text-danger"></small>
                        </div>

                        {{-- Content --}}
                        <div class="mb-3">
                            <label for="content" class="form-label fw-bold"><i class="bi bi-card-text me-1"></i>Content <span class="text-danger">*</span></label>
                            <textarea name="content" id="content" class="form-control" rows="8"></textarea>
                            <small id="contentError" class="text-danger"></small>
                        </div>

                        {{-- Publish At --}}
                        <div class="form-floating mb-3">
                            <input type="datetime-local" id="publish_at" name="publish_at" class="form-control">
                            <label for="publish_at"><i class="bi bi-calendar-event-fill me-2"></i>Publish At</label>
                            <small id="publish_atError" class="text-danger"></small>
                        </div>

                        {{-- Is Active --}}
                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" checked>
                            <label class="form-check-label" for="is_active"><i class="bi bi-check2-circle me-2"></i>Active</label>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-send-fill"></i> Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function () {
        $('#announcementForm').on('submit', function (e) {
            e.preventDefault();

            $('#titleError, #contentError, #publish_atError, #to_emailsError, #from_emailError').text('');

            let content = '';
            if (typeof tinymce !== 'undefined' && tinymce.get('content')) {
                content = tinymce.get('content').getContent();
            }

            const formData = {
                title: $('#title').val(),
                content: content,
                publish_at: $('#publish_at').val(),
                is_active: $('#is_active').is(':checked') ? 1 : 0,
                to_emails: $('#to_emails').val(),
                from_email: $('#from_email').val()
            };

            $.ajax({
                url: '{{ route("announcements.store") }}',
                method: 'POST',
                data: formData,
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'View Announcements'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '{{ route("announcements.index") }}';
                            }
                        });
                    }
                },
                error: function (xhr) {
                    const errors = xhr.responseJSON.errors;
                    if (errors) {
                        if (errors.title) $('#titleError').text(errors.title[0]);
                        if (errors.content) $('#contentError').text(errors.content[0]);
                        if (errors.to_emails) $('#to_emailsError').text(errors.to_emails[0]);
                        if (errors.from_email) $('#from_emailError').text(errors.from_email[0]);
                        if (errors.publish_at) $('#publish_atError').text(errors.publish_at[0]);
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
