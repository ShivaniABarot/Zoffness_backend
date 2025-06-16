<?php

namespace App\Exports;

use App\Models\EmailLog;
use Maatwebsite\Excel\Concerns\FromCollection;

class EmailLogsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return EmailLog::all();
    }
}
