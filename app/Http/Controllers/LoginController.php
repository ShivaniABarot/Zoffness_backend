<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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


    public function login_api(Request $request)
    {
        // dd(564658458);
        try {
            // Validate input
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

            // Revoke previous tokens if needed
            $user->tokens()->delete();

            // Create a new token
            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful.',
                'token' => $token,
                'user' => [
                    'username' => $user->username,
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
