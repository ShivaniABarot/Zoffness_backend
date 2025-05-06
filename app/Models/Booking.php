<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'parent_id',
        // 'student_id',
        'session_id',
        'timeslot_id',
        'package_id',
        'remaining_sessions',
        'status',
    ];

    /**
     * Define a relationship with the Session model.
     */
    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    /**
     * Define a relationship with the Timeslot model.
     */
    public function timeslot()
    {
        return $this->belongsTo(Timeslot::class);
    }

    /**
     * Define a relationship with the Package model.
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Define a relationship with the Student model.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Define a relationship with the User model (Parent).
     */
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Scope to filter bookings by status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter bookings by session.
     */
    public function scopeSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Scope to filter bookings by student.
     */
    public function scopeStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    
}
