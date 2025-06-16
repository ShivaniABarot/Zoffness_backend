<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoginLog;
use App\Models\EmailLog;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\LoginLogsExport;
use App\Exports\EmailLogsExport;
class LogController extends Controller
{
    public function index()
{
    return view('logs.index');
}

public function loginLogs()
{
    $logs = LoginLog::latest()->get();
    return view('logs.partials.login_logs', compact('logs'));
}

public function emailLogs()
{
    $logs = EmailLog::latest()->get();
    return view('logs.partials.email_logs', compact('logs'));
}

// Export Excel
public function exportLoginLogsExcel()
{
    return Excel::download(new LoginLogsExport, 'login_logs.xlsx');
}

public function exportEmailLogsExcel()
{
    return Excel::download(new EmailLogsExport, 'email_logs.xlsx');
}

// Export PDF
public function exportLoginLogsPdf()
{
    $logs = LoginLog::latest()->get();
    $pdf = Pdf::loadView('logs.exports.login_logs_pdf', compact('logs'));
    return $pdf->download('login_logs.pdf');
}

public function exportEmailLogsPdf()
{
    $logs = EmailLog::latest()->get();
    $pdf = Pdf::loadView('logs.exports.email_logs_pdf', compact('logs'));
    return $pdf->download('email_logs.pdf');
}

}
