<!DOCTYPE html>
<html>
<head>
    <title>Email Logs PDF</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #666;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <h2>Email Logs</h2>
    <table>
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
</body>
</html>
