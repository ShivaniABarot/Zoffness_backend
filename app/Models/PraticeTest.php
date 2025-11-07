<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PraticeTest extends Model
{

    protected $table = 'practice_tests';

    protected $fillable = [
        'stripe_id',
        'payment_status',
        'student_id',
        'test_type',
        'date',
        'parent_first_name',
        'parent_last_name',
        'parent_phone',
        'parent_email',
        'student_first_name',
        'student_last_name',
        'student_email',
        'school',
        'payment_method',
        'subtotal',
        'grade',
        'total',
        'country',
        'status',
    ];

    /**
     * Relationship to Payment
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'practice_test_id');
    }

    /**
     * Relationship to Student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
