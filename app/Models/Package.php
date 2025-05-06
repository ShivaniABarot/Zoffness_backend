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
     * Example: If a package can be associated with sessions, add the relationship here.
     */
    public function sessions()
    {
        return $this->belongsToMany(Session::class, 'packages_sessions');
    }
}
