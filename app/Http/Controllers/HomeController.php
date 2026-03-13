<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
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
