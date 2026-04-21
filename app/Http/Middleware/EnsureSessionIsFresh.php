<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSessionIsFresh
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return $next($request);
        }

        $lastActivity = (int) $request->session()->get('last_activity_at', 0);
        $lifetimeSeconds = max(1, (int) config('session.lifetime', 120)) * 60;

        if ($lastActivity > 0 && (time() - $lastActivity) >= $lifetimeSeconds) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Your session has expired. Please log in again.');
        }

        $request->session()->put('last_activity_at', time());

        return $next($request);
    }
}
