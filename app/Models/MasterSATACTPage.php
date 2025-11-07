<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSATACTPage extends Model
{
    use HasFactory;

    protected $table = 'sat_act_page_contents'; // 👈 matches your DB
    protected $fillable = [
        // 'section_type',
        'title',
        'subtitle',
        'description',
        'icon',
        'image_path',
        'point_text',
        'button_text',
        'button_link',
        'order_index',
        'status'
    ];
}
