<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5 class="mb-0">Login Logs</h5>
        <div>
            <a href="{{ route('logs.login.export.excel') }}" class="btn btn-success btn-sm">Export Excel</a>
            <a href="{{ route('logs.login.export.pdf') }}" class="btn btn-danger btn-sm">Generate PDF</a>
        </div>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>User Email</th>
                    <th>IP Address</th>
                    <th>Login Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr>
                    <td>{{ $log->email }}</td>
                    <td>{{ $log->ip_address }}</td>
                    <td>{{ $log->created_at }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
