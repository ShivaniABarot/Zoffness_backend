<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'testimonials';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'relationship',
        'rating',
        'testimonial',
        'consent',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'consent' => 'boolean',
        'rating' => 'integer',
    ];
}
