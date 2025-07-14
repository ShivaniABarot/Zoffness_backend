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
            'username' => [
                'required',
                'string',
                'unique:users,username',
            ],
            'email' => 'required|email|unique:users,email',
            'phone_no' => 'required ',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ];
    
        try {
            $validated = $request->validate($rules);
    
            $user = User::create([
                'username' => trim($validated['username']),
                'email' => $validated['email'],
                'phone_no' => $validated['phone_no'],
                'password' => bcrypt($validated['password']), // Hash the password
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
            ], 201); // 201 Created
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422); // 422 Unprocessable Entity
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }
    

    protected function sendRegistrationEmail(User $user)
    {
        Mail::to($user->email)->send(new RegistrationSuccessMail($user));
    }
}
