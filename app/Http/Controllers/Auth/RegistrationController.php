<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Mail\RegistrationEmail;



class RegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'phone' => 'nullable|string|max:20',
            'gender' => 'required|in:male,female,other'
        ]);

        $verificationToken = Str::random(50);

        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_USER,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'verification_token' => $verificationToken
        ]);

        // Send verification email
        $mailData = [
            'name' => $user->name,
            'token' => $verificationToken,
            'verification_url' => route('verify.email', ['token' => $verificationToken]),
        ];
        Mail::to($user->email)->send(new RegistrationEmail($mailData));

        return redirect()->route('login')
            ->with('success', 'Registration successful. Please verify your email before logging in.');
    }

    public function verifyEmail($token)
    {
        try {
            Log::info('Email verification started', ['token' => substr($token, 0, 10) . '...']);

            // Find user by token
            $user = User::where('verification_token', $token)->first();

            if (!$user) {
                Log::warning('Verification token not found', ['token' => substr($token, 0, 10) . '...']);
                return redirect('/login')->with('error', 'Invalid or expired verification link.');
            }

            // Check if already verified
            if ($user->is_verified) {
                Log::info('User already verified', ['user_id' => $user->id]);
                return redirect('/login')->with('success', 'Email already verified. You can now log in.');
            }

            // Update using query builder for more control
            $updated = DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'is_verified' => 1,
                    'verification_token' => null,
                    'email_verified_at' => DB::raw('NOW()'),
                ]);

            if ($updated) {
                Log::info('Email verified successfully', ['user_id' => $user->id]);
                return redirect('/login')->with('success', 'Email verified successfully! You can now log in.');
            } else {
                Log::error('Failed to update user during verification', ['user_id' => $user->id]);
                return redirect('/login')->with('error', 'Verification failed. Please try again.');
            }
        } catch (\Throwable $e) {
            Log::error('Email verification exception', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return redirect('/login')->with('error', 'An error occurred. Please contact support.');
        }
    }
}
