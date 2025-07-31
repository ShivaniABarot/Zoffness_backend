<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    // Specify the table associated with the model
    protected $table = 'sessions';

    // Define the fillable fields for mass assignment
        protected $fillable = [
            'title',
            'session_type',
            'price_per_slot',
            'max_capacity',
            'date',
            'status',
        ];

    /**
     * Get the tutor that owns the session.
     */
    // public function tutor()
    // {
    //     return $this->belongsTo(Tutor::class, 'tutor_id');
    // }

    /**
     * Get the bookings associated with the session.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the timeslots associated with the session.
     */
    // public function timeslots()
    // {
    //     return $this->hasMany(Timeslot::class);
    // }

    /**
     * Get the package-sessions relationships.
     */
    public function packageSessions()
    {
        return $this->belongsToMany(Package::class, 'packages_sessions', 'session_id', 'package_id');
    }
}
