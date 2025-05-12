<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SAT_ACT_Packages extends Model
{
    use HasFactory;

    // Define the table name if it does not follow the default convention (optional)
    protected $table = 'sat_act_course';

    // Mass assignable attributes
    protected $fillable = [
        'name',
        'price',
        // 'number_of_sessions',
        'description',
    ];

    /**
     * Get the session associated with the timeslot.
     */
    public function session()
    {
        return $this->belongsTo(Session::class);
    }
}
