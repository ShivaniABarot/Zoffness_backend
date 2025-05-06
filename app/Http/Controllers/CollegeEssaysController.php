<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CollegeEssays;


class CollegeEssaysController extends Controller
{
    public function index()
    {
        $collegessays = CollegeEssays::all();
        return view('inquiry.college_essays', compact('collegessays'));
    }
}
