<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'student_first_name',
        'student_last_name',
        'email',
        'payment_type',
        'payment_amount',
        'note',
        'cardholder_name',
        'card_number',
        'card_exp_date',
        'cvv',
        'bill_address',
        'city',
        'state',
        'zip_code',
        'user_id',
    ];

    protected $casts = [
        'payment_amount' => 'decimal:2',
    ];
}
