<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollegeEssays extends Model
{
    protected $table = 'college_essays';
    protected $fillable = [
        'parent_first_name',
        'parent_last_name',
        'parent_phone',
        'parent_email',
        'student_first_name',
        'student_last_name',
        'student_email',
        'school',
        'sessions',
        'packages',
        'student_id',
        'exam_date'
    ];
}
