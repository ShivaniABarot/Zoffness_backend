<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollegeEssays extends Model
{
    use HasFactory;

    protected $table = 'college_essays';

    protected $fillable = [
        'parent_first_name',
        'parent_last_name',
        'parent_phone',
        'parent_email',
        'student_first_name',
        'student_last_name',
        'student_email',
        'school',
        'sessions',
        'packages', // Assuming this is a JSON or comma-separated string of package IDs
        'student_id',
        'exam_date'
    ];

    public function packages()
    {
        // Handle the packages field (assuming it stores IDs as JSON or comma-separated)
        $ids = $this->packages 
            ? (is_array($this->packages) ? $this->packages : (is_string($this->packages) ? explode(',', $this->packages) : []))
            : [];

        // Ensure IDs are valid integers
        $ids = array_filter(array_map('intval', $ids));

        return CollageEssaysPackage::whereIn('id', $ids)->get();
    }
}