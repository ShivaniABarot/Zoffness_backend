<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;
    protected $table = 'announcements';


    protected $fillable = [
        'title',
        'content',
        'publish_at',
        'is_active',
        'created_at',
        'updated_at',

    ];
}
