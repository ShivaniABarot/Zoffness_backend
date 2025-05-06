<?php

// app/Models/Student.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_name',
        'parent_phone',
        'parent_email',
        'student_name',
        'student_email',
        'school',
        'bank_name',
        'account_number',
    ];
}
