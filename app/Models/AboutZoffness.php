<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutZoffness extends Model
{
    use HasFactory;

    protected $table = 'about_zoffness';
    protected $fillable = [
        'title',
        'description',
        'image_main',
        'image_1',
        'image_2',
        'cta_text',
        'cta_link',
    ];
}
