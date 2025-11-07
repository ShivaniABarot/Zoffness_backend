<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollageEssaysPackage extends Model
{
    use HasFactory;

    protected $table = 'collage_essays_packages';

    protected $fillable = [
        'id',
        'name',
        'description',
        'price',
        'status',
    ];

    // Define the inverse relationship
    public function collegeEssay()
    {
        return $this->belongsTo(CollegeEssays::class, 'id', 'packages');
    }
}