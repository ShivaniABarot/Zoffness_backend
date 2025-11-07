<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OurProgram extends Model
{
    use HasFactory;
    protected $table = 'our_programs';

    protected $fillable = [
        'title',
        'short_description',
        'icon_image',
        'link',
    ];
}
