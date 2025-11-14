<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationFee extends Model
{
    protected $table = 'consultation_fees';

    protected $fillable = [
        'name',
        'amount',
        'description',
        'status',
    ];
}
