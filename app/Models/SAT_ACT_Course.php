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
        'name',
        'description',
        'price',
        // Add other fillable attributes as needed
    ];

    /**
     * Get the packages associated with this course.
     */
    public function packages()
    {
        return $this->hasMany(Package::class, 'id');
    }
}