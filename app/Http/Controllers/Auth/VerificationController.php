<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;

class VerificationController extends Controller
{
    public function verify($token)
    {
        $user = User::where('verification_token', $token)->firstOrFail();

        $user->update([
            'is_verified' => true,
            'verification_token' => null
        ]);

        return redirect()->route('login')->with('success', 'Email verified successfully! You can now login.');
    }
}
