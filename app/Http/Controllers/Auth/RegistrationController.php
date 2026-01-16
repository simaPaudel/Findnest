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
            'role' => 'required|in:user,owner',
            'phone' => 'nullable|string|max:20',
            'gender' => 'required|in:male,female,other'
        ]);

        $verificationToken = Str::random(50);

        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'verification_token' => $verificationToken
        ]);

        // Send verification email
        $mailData = [
            'name' => $user->name,
            'token' => $verificationToken
        ];
        Mail::to($user->email)->send(new RegistrationEmail($mailData));

        return redirect()->route('login')
            ->with('success', 'Registration successful. Please verify your email before logging in.');
    }

    public function verifyEmail($token)
    {
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            toastr()->error('Invalid or expired verification link.');
            return redirect()->route('login');
        }

        $user->update([
            'is_verified' => 1,
            'verification_token' => null,
            'email_verified_at' => now(),
        ]);

        toastr()->success('Email verified successfully. You can now log in.');
        return redirect()->route('login');
    }
}
