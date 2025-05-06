<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'parent_id',
        'package_id',
        'session_id',
        'payment_method',
        'amount',
        'status',
        'transaction_id',
    ];

    /**
     * Relationships.
     */// Payment.php (Payment Model)
   // In Payment model (app/Models/Payment.php)

public function student()
{
    return $this->belongsTo(Student::class, 'parent_id'); // 'parent_id' is the foreign key referring to the student
}

    public function package()
    {
        return $this->belongsTo(Package::class); 
        
    }

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

}
