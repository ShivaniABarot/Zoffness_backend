<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OurApproach extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_title',
        'description',
        'image',
        'highlights',
    ];

    protected $casts = [
        'highlights' => 'array', // if storing bullet points as JSON
    ];
}
