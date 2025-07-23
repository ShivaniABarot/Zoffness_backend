<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedule';
    protected $fillable = [
        'name',
        'email',
        'phone_no',
        'date',
        'time_slot',
        'primary_interest',
        'fees',
    ];
}
