<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CollegeAdmission;

class CollegeAdmissionController extends Controller
{
    public function index()
    {
        $collegeadmission = CollegeAdmission::all();
        return view('inquiry.college_admission', compact('collegeadmission'));
    }
}
