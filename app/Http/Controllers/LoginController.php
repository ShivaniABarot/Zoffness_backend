<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Check if this is an AJAX request
        $isAjax = $request->ajax() || $request->wantsJson();

        try {
            // Validate input
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            // Extract credentials
            $credentials = $request->only('email', 'password');
            $remember = $request->has('remember');

            // Check if user exists by email
            $user = User::where('email', $credentials['email'])->first();

            // If user does not exist, return error
            if (!$user) {
                if ($isAjax) {
                    return response()->json(['success' => false, 'message' => 'Email not found.'], 404);
                } else {
                    return back()->withErrors(['email' => 'Email not found.'])->withInput();
                }
            }

            // Check if the entered password matches the hashed password in the database
            if (Hash::check($credentials['password'], $user->password)) {
                Auth::login($user, $remember);

                if ($isAjax) {
                    return response()->json(['success' => true, 'message' => 'Login successful.']);
                } else {
                    return redirect()->intended('/dashboard')->with('success', 'Login successful.');
                }
            } else {
                if ($isAjax) {
                    return response()->json(['success' => false, 'message' => 'Incorrect password.'], 401);
                } else {
                    return back()->withErrors(['password' => 'Incorrect password.'])->withInput();
                }
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            } else {
                throw $e; // Let Laravel handle the validation error for traditional form
            }
        }
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forget_password');
    }
    
    // Handle sending reset link
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
    
        try {
            $user = User::where('email', $request->email)->first();
    
            if (!$user) {
                return response()->json([
                    'message' => 'We cannot find a user with that email address.',
                ], 422);
            }
            // dd($user);
            $token = Password::broker()->createToken($user);
            // dd(564645);
            Mail::to($user->email)->send(new ResetPasswordMail($user, $token));
            // dd($token);
            return response()->json([
                'message' => 'A password reset link has been sent to your email.',
            ], 200);
    
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Forgot Password Error: '.$e->getMessage());
    
            return response()->json([
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }
    // Show reset password form
    public function showResetPasswordForm($token)
    {
        return view('auth.reset_password', ['token' => $token]);
    }
    
    // Handle new password submission
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);
    
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );
    
        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Password reset successfully. You can now log in.');
        } else {
            return back()->withErrors(['email' => [__($status)]]);
        }
    }


    public function login_api(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);
    
            $user = User::where('email', $request->email)->first();
    
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email not found.'
                ], 404);
            }
    
            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Incorrect password.'
                ], 401);
            }
    
            $user->tokens()->delete(); // revoke previous tokens
            $token = $user->createToken('api_token')->plainTextToken;
    
            return response()->json([
                'success' => true,
                'message' => 'Login successful.',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'firstname' => $user->firstname,
                    'lastname' => $user->lastname,
                    'phone_no' => $user->phone_no,
                    'email' => $user->email,
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
}
