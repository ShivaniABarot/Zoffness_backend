<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timeslot extends Model
{
    use HasFactory;

    // Define the table name if it does not follow the default convention (optional)
    protected $table = 'timeslots';

    // Mass assignable attributes
    protected $fillable = [
        'session_id',
        'room',
        'date',
        'start_time',
        'end_time',
        'available_seats',
    ];

    /**
     * Get the session associated with the timeslot.
     */
    public function session()
    {
        return $this->belongsTo(Session::class);
    }
}
