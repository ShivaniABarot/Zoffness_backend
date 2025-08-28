<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SAT_ACT_Course extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'sat_act_course_reg';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_firstname',
        'parent_lastname',
        'parent_phone',
        'parent_email',
        'student_firstname',
        'student_lastname',
        'student_email',
        'school',
        'amount',
        'package_name',
        'student_id',
        'exam_date',
        // 'stripe_id' // also add this if column exists
    ];
    
    
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Get the packages associated with this course.
     */
    public function packages()
    {
        return $this->hasMany(Package::class, 'id');
    }
}