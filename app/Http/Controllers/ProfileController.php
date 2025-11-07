<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showProfile()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'firstname' => ['required', 'string'],
            'lastname'  => ['required', 'string'],
            'phone_no'  => ['required', 'string'],
            'email'     => ['required', 'email'],
        ]);

        try {
            $user->update($validated);
            return back()->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            Log::error('Profile update failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update profile. Please try again.');
        }
    }

    /** Update Password (no confirmation) */
    public function updatePassword(Request $request)
{
    $request->validate([
        'new_password' => ['required', 'string', 'min:8'],
    ]);

    try {
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('login')->with('success', 'Password updated successfully!');
    } catch (\Exception $e) {
        Log::error('Password update failed: ' . $e->getMessage());
        return back()->with('error', 'Failed to update password. Please try again.');
    }
}

}
