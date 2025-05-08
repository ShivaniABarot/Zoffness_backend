<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PraticeTest extends Model
{
    use HasFactory;

    protected $table = 'practice_tests';

    protected $fillable = [
        'parent_first_name',
        'parent_last_name',
        'parent_phone',
        'parent_email',
        'student_first_name',
        'student_last_name',
        'student_email',
        'school',
        'subtotal',
        'test_type',
        'date'
    ];

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_pratice_test');
    }
}
