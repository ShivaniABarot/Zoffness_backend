<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExecutiveCoaching extends Model
{
    protected $table = 'executive_function_coaching';
    protected $fillable = [
        'parent_first_name',
        'parent_last_name',
        'parent_phone',
        'parent_email',
        'student_first_name',
        'student_last_name',
        'student_email',
        'school',
        'package_type',
        'subtotal',
        'exam_date',
        'student_id',
        'packages' // Keep this if it exists, but we'll prioritize package_type
    ];
    
    public function package()
    {
        return $this->hasOne(ExecutivePackage::class, 'name', 'package_type');
    }
}