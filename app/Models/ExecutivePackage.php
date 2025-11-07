<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExecutivePackage extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'executive_function_packages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'price',
        'status',
    ];

    /**
     * Get the packages associated with this course.
     */
    public function packages()
{
    return $this->hasMany(Package::class, 'id');
}
    
public function executiveCoachings()
{
    return $this->hasMany(ExecutiveCoaching::class, 'package_type', 'name');
}
    
}