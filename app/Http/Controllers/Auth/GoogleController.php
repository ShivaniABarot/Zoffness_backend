<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Check if user already exists
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Create a new user
                $user = User::create([
                    'name'     => $googleUser->getName(),
                    'email'    => $googleUser->getEmail(),
                    'google_id'=> $googleUser->getId(),
                    'password' => bcrypt(Str::random(16)), // Random password
                ]);
            }

            Auth::login($user);

            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Failed to login with Google.');
        }
    }
}
