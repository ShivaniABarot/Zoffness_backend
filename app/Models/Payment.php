<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'stripe_payment_intent_id',
        'stripe_charge_id',
        'amount',  // Note: your table has this as 'payment_amount' initially
        'currency',
        'status',
        'practice_test_id',
        'metadata',
        'paid_at',
        // Keep existing fields for backward compatibility
        'parent_first_name',
        'parent_last_name',
        'parent_phone',
        'parent_email',
        'student_first_name',
        'student_last_name',
        'email',
        'payment_type',
        'note',
        'bill_address',
        'city',
        'state',
        'zip_code',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
        'paid_at' => 'datetime',
    ];

    /**
     * Relationship to PracticeTest
     */
    public function practiceTest()
    {
        return $this->belongsTo(PracticeTest::class, 'practice_test_id');
    }

    /**
     * Relationship to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
