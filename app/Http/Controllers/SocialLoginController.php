<?php

namespace App\Http\Controllers;

use Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class SocialLoginController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();

            // Find or create user
            $user = User::firstOrCreate(
                ['email' => $socialUser->getEmail()],
                [
                    'name' => $socialUser->getName(),
                    'username' => $socialUser->getNickname() ?? $socialUser->getEmail(), // or generate some username
                    'password' => bcrypt(Str::random(16)), // random password if needed
                ]
            );
            

            Auth::login($user);
            return redirect('/dashboard');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Login failed: ' . $e->getMessage());
        }
    }
}
