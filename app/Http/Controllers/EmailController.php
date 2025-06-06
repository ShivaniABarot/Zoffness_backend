<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class EmailController extends Controller
{
    public function sendPasswordResetLink(Request $request)
{
    $request->validate([
        'email' => 'required|email',
    ]);

    $user = User::where('email', $request->email)->first();
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Please enter your registered email address.',
        ], 404);
    }

    try {
        $status = Password::sendResetLink($request->only('email'));
        \Log::info('Password reset status: ' . $status);
        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => 'Reset link sent to your email.',
            ]);
        } else {
            \Log::error('Password reset failed with status: ' . $status);
            return response()->json([
                'success' => false,
                'message' => 'Unable to send reset link. Please try again later.',
            ], 500);
        }
    } catch (\Exception $e) {
        \Log::error('Error sending password reset link: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while sending the reset link. Please try again later.',
        ], 500);
    }
}

    public function resetPassword(Request $request)
    {
        $request->validate(
            [
                'email' => [
                    'required',
                    'email',
                    'exists:users,email', // Ensure the email exists in the 'users' table
                ],
                'password' => [
                    'required',
                    'string',
                    'min:6',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*\W).+$/', // Updated regex for password validation
                ],
                'password_confirmation' => 'required|same:password',
            ],
            [
                // Custom validation messages
                'email.exists' => 'Please enter your registered email address.',
                'password.regex' => 'The password must contain at least 1 uppercase letter, 1 lowercase letter, 1 special character, 1 number, and be at least 6 characters long.',
                'password_confirmation.same' => 'The password confirmation does not match.',
            ],
        );

        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
            // Since the User model has a setPasswordAttribute mutator that already hashes the password,
            // we should NOT use Hash::make here to avoid double hashing
            $user->forceFill([
                'password' => $password, // The model's mutator will hash this
            ])->save();

            $user->setRememberToken(Str::random(60));
        });

        return $status == Password::PASSWORD_RESET ? redirect()->route('login')->with('status', 'Password reset successful.') : back()->withErrors(['email' => [__($status)]]);
    }

    public function showResetForm($token)
    {
        return view('auth.reset_password', ['token' => $token]);
    }
}
