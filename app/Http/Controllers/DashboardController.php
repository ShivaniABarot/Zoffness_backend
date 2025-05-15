<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Tutor;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('dashboard');
    }

    
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }


   public function index()
{
    $studentCount = Student::count();
    $tutorCount = Tutor::count();

    return view('dashboard', compact('studentCount', 'tutorCount'));
}

}
