<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Property;
use App\Models\Review;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalOwners = User::where('role', 'owner')->count();
        $totalProperties = Property::count();
        $pendingProperties = Property::where('status', 'pending')->count();
        $approvedProperties = Property::where('status', 'approved')->count();
        $rejectedProperties = Property::where('status', 'rejected')->count();
        $totalBookings = Booking::count();
        $totalReviews = Review::count();

        $recentProperties = Property::with('owner')
            ->latest()
            ->take(5)
            ->get();

        $recentBookings = Booking::with(['property', 'user'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalOwners',
            'totalProperties',
            'pendingProperties',
            'approvedProperties',
            'rejectedProperties',
            'totalBookings',
            'totalReviews',
            'recentProperties',
            'recentBookings'
        ));
    }
}
