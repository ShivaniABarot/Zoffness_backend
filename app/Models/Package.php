<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'price',
        'number_of_sessions',
        'description',
    ];

    /**
     * Define any relationships if applicable.
     */
    public function sessions()
    {
        return $this->belongsToMany(Session::class, 'packages_sessions');
    }

    /**
     * Define the many-to-many relationship with PracticeTest model
     */
    public function practiceTests()
    {
        return $this->belongsToMany(PraticeTest::class, 'package_practice_test', 'package_id', 'practice_test_id');
    }
}