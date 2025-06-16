<?php

// app/Models/LoginLog.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoginLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'ip_address',
        'logged_in_at',
    ];

    protected $casts = [
        'logged_in_at' => 'datetime',
    ];

    // Optional: Define relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
