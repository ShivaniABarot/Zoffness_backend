@extends('layouts.app')

@section('content')


<div class="container-xxl">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">System Logs</h3>
        <div>
            <button id="btn-login-logs" class="btn btn-outline-primary me-2">Login Logs</button>
            <button id="btn-email-logs" class="btn btn-outline-secondary">Email Logs</button>
        </div>
    </div>

    <div id="logs-content">
        <p>Select a log type to view data.</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#btn-login-logs').on('click', function () {
        $('#logs-content').load('{{ route("logs.login") }}');
    });

    $('#btn-email-logs').on('click', function () {
        $('#logs-content').load('{{ route("logs.email") }}');
    });
});
</script>
@endpush
