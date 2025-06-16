<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5 class="mb-0">Email Logs</h5>
        <div>
            <a href="{{ route('logs.email.export.excel') }}" class="btn btn-success btn-sm">Export Excel</a>
            <a href="{{ route('logs.email.export.pdf') }}" class="btn btn-danger btn-sm">Generate PDF</a>
        </div>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>To</th>
                    <th>Subject</th>
                    <th>Sent At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr>
                    <td>{{ $log->to }}</td>
                    <td>{{ $log->subject }}</td>
                    <td>{{ $log->created_at }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
