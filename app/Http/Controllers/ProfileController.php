<?php

// ProfileController.php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function showProfile()
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Pass the user data to the profile view
        return view('profile', compact('user'));
    }
}
