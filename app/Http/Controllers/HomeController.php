<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Redirect authenticated users to their respective dashboards
        if (Auth::check()) {
            $user = Auth::user();
            
            switch ($user->role) {
                case User::ROLE_ADMIN:
                    return redirect()->route('admin.dashboard');
                case User::ROLE_OWNER:
                    return redirect()->route('owner.dashboard');
                case User::ROLE_USER:
                default:
                    return redirect()->route('user.dashboard');
            }
        }

        // Fetch latest 6 approved properties for featured listings
        $featuredListings = Property::where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Fetch stats
        $stats = [
            'totalListings' => Property::where('status', 'approved')->count(),
            'verifiedListings' => Property::where('status', 'approved')
                ->where('is_verified', 1)
                ->count(),
            'activeOwners' => User::where('role', 'owner')->count(),
        ];

        return view('welcome', compact('featuredListings', 'stats'));
    }
}
