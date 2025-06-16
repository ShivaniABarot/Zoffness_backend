<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Mail\RegistrationSuccessMail;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $isAjax = $request->ajax() || $request->wantsJson();

        $rules = [
            'username' => [
                'required',
                'string',
                'max:255',
                'unique:users,username',
                'regex:/^[a-zA-Z0-9._-]+$/'
            ],
            'email' => 'required|email|max:255|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',   
                'regex:/[A-Z]/',    
                'regex:/[0-9]/',     
            ],
        ];

        try {
            $validated = $request->validate($rules);

            $user = User::create([
                'username' => trim($validated['username']),
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']), // ✅ Hash the password here
            ]);

            try {
                $this->sendRegistrationEmail($user);
                $message = 'Account created successfully. Check your email for confirmation.';
            } catch (\Exception $emailEx) {
                Log::error('Email failed: ' . $emailEx->getMessage());
                $message = 'Account created successfully, but the confirmation email failed to send.';
            }

            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }

            return redirect()->route('login')->with('success', $message);

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error.',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registration failed: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Registration failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    // protected function sendRegistrationEmail(User $user)
    // {
    //     Mail::to($user->email)->send(new RegistrationSuccessMail($user));
    // }

    protected function sendRegistrationEmail(User $user)
{
    $mail = new RegistrationSuccessMail($user);

    // Send the email
    \Mail::to($user->email)->send($mail);

    // Log it manually (basic log)
    \App\Models\EmailLog::create([
        'to'      => $user->email,
        'subject' => method_exists($mail, 'build') ? $mail->build()->subject : 'Registration Success',
        'body'    => method_exists($mail, 'render') ? $mail->render() : null,
        'status'  => 'sent',
    ]);
}

}
