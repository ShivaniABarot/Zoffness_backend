<?php

namespace App\Exports;

use App\Models\LoginLog;
use Maatwebsite\Excel\Concerns\FromCollection;

class LoginLogsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return LoginLog::all();
    }
}
