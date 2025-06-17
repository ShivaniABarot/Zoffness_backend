<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Session;
class Tutor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'designation',
        'bio',
        'email',
    ];

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    // In Tutor.php model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
