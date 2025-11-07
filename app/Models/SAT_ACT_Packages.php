<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SAT_ACT_Packages extends Model
{
    use HasFactory;
    protected $table = 'sat_act_course';

    // Mass assignable attributes
    protected $fillable = [
        'name',
        'price',
        'status',
        'description',
    ];

   
    public function session()
    {
        return $this->belongsTo(Session::class);
    }
}
