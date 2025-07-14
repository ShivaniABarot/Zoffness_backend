<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Authenticatable implements CanResetPasswordContract
{
    use HasApiTokens, Notifiable, CanResetPassword;

    protected $fillable = [
        'id',
        'username',
        'email',
        'password',
        'phone_no'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
// app/Models/User.php

public function getFilamentName(): string
{
    return $this->username ?? $this->name ?? 'Admin';
}


    // Removed automatic password hashing to prevent double hashing
}