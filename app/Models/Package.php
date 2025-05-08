<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $table = 'packages';

    protected $fillable = [
        'name',
        'price',
        'number_of_sessions',
        'description',
    ];

    public function sessions()
    {
        return $this->belongsToMany(Session::class, 'packages_sessions');
    }

    public function praticeTests()
    {
        return $this->belongsToMany(PraticeTest::class, 'package_pratice_test');
    }
}
