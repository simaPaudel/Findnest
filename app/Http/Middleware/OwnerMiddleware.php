<?php

namespace App\Http\Middleware;

use Brian2694\Toastr\Facades\Toastr;
use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OwnerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user?->isBlocked()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            Toastr::error('Your account has been deactivated. Please contact support.');

            return redirect()->route('login');
        }

        if ($user->role === User::ROLE_ADMIN) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role !== User::ROLE_OWNER) {
            return redirect()
                ->route('user.host-application.show')
                ->with('error', 'Please submit and get approval for your host application before accessing owner pages.');
        }

        return $next($request);
    }
}
