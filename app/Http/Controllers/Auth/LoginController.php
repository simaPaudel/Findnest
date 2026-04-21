<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->isBlocked()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                Toastr::error('Your account has been deactivated. Please contact support.');
                return redirect()->route('login');
            }

            if (!$user->is_verified) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                Toastr::error('Please verify your email before logging in.');
                return redirect()->route('login');
            }

            // Redirect based on role
            switch ($user->role) {
                case User::ROLE_ADMIN:
                    Toastr::success('Welcome Admin!');
                    return redirect()->route('admin.dashboard');

                case User::ROLE_OWNER:
                    Toastr::success('Welcome Property Owner!');
                    return redirect()->route('owner.dashboard');

                default:
                    Toastr::success('Login successful!');
                    return redirect()->route('user.dashboard');
            }
        }
        
        // Invalid credentials - redirect back with error
        return back()->withInput($request->only('email'))->withErrors([
            'email' => 'Invalid email or password.',
        ]);
    }


    public function logout(Request $request)
    {
        $isSessionExpired = $request->string('logout_reason')->toString() === 'session_expired';

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($isSessionExpired) {
            return redirect()->route('login')->with('error', 'Your session expired. Please log in again.');
        }

        Toastr::success('Logged out successfully.');
        return redirect()->route('login');
    }
}
