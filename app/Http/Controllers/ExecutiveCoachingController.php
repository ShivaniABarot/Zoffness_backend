<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExecutiveCoaching;


class ExecutiveCoachingController extends Controller
{
    public function index()
    {
        $coaching = ExecutiveCoaching::all();
        return view('inquiry.executive_function', compact('coaching'));
    }
}
