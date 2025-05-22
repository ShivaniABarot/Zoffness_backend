<?php

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

    public function collegeAdmissions()
    {
        return $this->hasMany(CollegeAdmission::class, 'student_email', 'student_email');
    }

    public function collegeEssays()
    {
        return $this->hasMany(CollegeEssays::class, 'student_email', 'student_email');
    }

    public function enrollments()
    {
        return $this->hasMany(Enroll::class, 'student_email', 'student_email');
    }

    public function executiveFunctionCoaching()
    {
        // Assuming this table exists; adjust as needed since it wasn't provided
        return $this->hasMany(ExecutiveCoaching::class, 'student_email', 'student_email');
    }

    public function satActCourseRegistrations()
    {
        return $this->hasMany(SAT_ACT_Course::class, 'student_email', 'student_email');
    }

    public function practiceTests()
    {
        return $this->hasMany(PraticeTest::class, 'student_email', 'student_email');
    }
}