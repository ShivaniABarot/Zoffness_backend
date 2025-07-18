<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\RegistrationSuccessMail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $isAjax = $request->ajax() || $request->wantsJson();
    
        $rules = [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email',
            'phone_no' => 'required|string',
            'password' => 'required|string|confirmed',
        ];
    
        try {
            $validated = $request->validate($rules);
    
            $user = User::create([
                'firstname' => trim($validated['firstname']),
                'lastname' => trim($validated['lastname']),
                'email' => $validated['email'],
                'phone_no' => $validated['phone_no'],
                'password' => bcrypt($validated['password']),
            ]);
    
            try {
                $this->sendRegistrationEmail($user);
                $message = 'Account created successfully. Check your email for confirmation.';
            } catch (\Exception $emailEx) {
                \Log::error('Email failed: ' . $emailEx->getMessage());
                $message = 'Account created successfully. (Email will be sent shortly)';
            }
    
            return response()->json([
                'success' => true,
                'message' => $message
            ], 201);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    

    protected function sendRegistrationEmail(User $user)
    {
        Mail::to($user->email)->send(new RegistrationSuccessMail($user));
    }
}
