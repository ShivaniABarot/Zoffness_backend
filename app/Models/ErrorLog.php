<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    protected $fillable = [
        'error_type',
        'error_message',
        'error_stack',
        'user_email',
        'student_name',
        'stripe_payment_id',
        'form_data',
        'browser_info',
        'severity',
        'resolved',
        'resolved_at',
        'resolution_notes',
    ];

    protected $casts = [
        'form_data' => 'array',
        'browser_info' => 'array',
        'resolved' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    /**
     * Get unresolved critical errors
     */
    public static function criticalUnresolved()
    {
        return self::where('severity', 'critical')
                   ->where('resolved', false)
                   ->orderBy('created_at', 'desc')
                   ->get();
    }

    /**
     * Mark error as resolved
     */
    public function markResolved($notes = null)
    {
        $this->update([
            'resolved' => true,
            'resolved_at' => now(),
            'resolution_notes' => $notes,
        ]);
    }
}
