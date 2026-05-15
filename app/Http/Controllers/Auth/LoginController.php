<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

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

            return $this->redirectAuthenticatedUser($request, $user);
        }
        
        // Invalid credentials - redirect back with error
        return back()->withInput($request->only('email'))->withErrors([
            'email' => 'Invalid email or password.',
        ]);
    }

    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (Throwable $e) {
            Log::error('Google OAuth redirect failed.', [
                'error' => $e->getMessage(),
            ]);

            Toastr::error('Google sign in is temporarily unavailable. Please use email and password.');

            return redirect()->route('login')
                ->with('error', 'Google sign in is temporarily unavailable. Please use email and password.');
        }
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = $this->findOrCreateGoogleUser($googleUser);

            Auth::login($user);
            $request->session()->regenerate();

            return $this->redirectAuthenticatedUser($request, $user);
        } catch (Throwable $e) {
            Log::warning('Google OAuth callback failed.', [
                'error' => $e->getMessage(),
            ]);

            Toastr::error('Unable to continue with Google. Please try again or use email and password.');

            return redirect()->route('login')
                ->with('error', 'Unable to continue with Google. Please try again or use email and password.');
        }
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

    private function findOrCreateGoogleUser(\Laravel\Socialite\Contracts\User $googleUser): User
    {
        $email = Str::lower(trim((string) $googleUser->getEmail()));
        $name = Str::substr(trim((string) $googleUser->getName()), 0, 100);
        $isGoogleEmailVerified = (bool) data_get($googleUser->user, 'email_verified', data_get($googleUser->user, 'verified_email', true));

        Validator::make([
            'email' => $email,
            'name' => $name,
            'email_verified' => $isGoogleEmailVerified,
        ], [
            'email' => ['required', 'email:rfc', 'max:150'],
            'name' => ['nullable', 'string', 'max:100'],
            'email_verified' => ['accepted'],
        ], [
            'email.required' => 'Google did not provide an email address.',
            'email.email' => 'Google did not provide a valid email address.',
            'email_verified.accepted' => 'Google could not verify this email address.',
        ])->validate();

        $user = User::where('email', $email)->first();

        if ($user) {
            if (! $user->is_verified || ! $user->email_verified_at) {
                $user->forceFill([
                    'is_verified' => true,
                    'email_verified_at' => $user->email_verified_at ?: now(),
                    'verification_token' => null,
                ])->save();
            }

            return $user;
        }

        return User::create([
            'name' => $name !== '' ? $name : Str::substr(Str::before($email, '@'), 0, 100),
            'email' => $email,
            'password' => Hash::make(Str::random(48)),
            'role' => User::ROLE_USER,
            'is_verified' => true,
            'email_verified_at' => now(),
            'verification_token' => null,
        ]);
    }

    private function redirectAuthenticatedUser(Request $request, User $user)
    {
        if ($user->isBlocked()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            Toastr::error('Your account has been deactivated. Please contact support.');
            return redirect()->route('login');
        }

        if (! $user->is_verified) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            Toastr::error('Please verify your email before logging in.');
            return redirect()->route('login');
        }

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
}
