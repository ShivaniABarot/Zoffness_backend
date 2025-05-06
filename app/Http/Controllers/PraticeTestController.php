<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PraticeTest;

class PraticeTestController extends Controller
{
    public function index()
    {
        $praticetest = PraticeTest::all();
        return view('inquiry.pratice_test', compact('praticetest'));
    }
}
