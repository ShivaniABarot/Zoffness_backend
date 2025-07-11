<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollegeAdmission extends Model
{
    protected $table = 'college_admissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'parent_first_name',
        'parent_last_name',
        'parent_phone',
        'parent_email',
        'student_first_name',
        'student_last_name',
        'student_email',
        'school',
        'subtotal',
        'initial_intake',
        'five_session_package',
        'ten_session_package',
        'fifteen_session_package',
        'twenty_session_package',
        'packages',
        'student_id'
    ];

}
