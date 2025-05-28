<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timeslot extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'session_id',
        'room',
        'date',
        'start_time',
        'end_time',
        'available_seats',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Get the session associated with the timeslot.
     */
    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    /**
     * Get the bookings associated with the timeslot.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
