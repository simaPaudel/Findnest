<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Booking;
use App\Models\Review;
use App\Models\RoommatePreference;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UserDashboardController extends Controller
{
    /**
     * Display the user dashboard with comprehensive statistics.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $userId = Auth::id();

        // Featured listings - latest 4 approved properties
        $featuredListings = Property::where('status', 'approved')
            ->latest()
            ->take(4)
            ->get();

        // Booking statistics
        $activeBookingsCount = Booking::where('user_id', $userId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        $confirmedBookingsCount = Booking::where('user_id', $userId)
            ->where('status', 'confirmed')
            ->count();

        $cancelledBookingsCount = Booking::where('user_id', $userId)
            ->where('status', 'cancelled')
            ->count();

        // Recent bookings with property details
        $recentBookings = Booking::where('user_id', $userId)
            ->with('property')
            ->latest()
            ->take(5)
            ->get();

        // Recent reviews by user
        $recentReviews = Review::where('user_id', $userId)
            ->with('property')
            ->latest()
            ->take(3)
            ->get();

        // Roommate preference check
        $roommatePrefExists = RoommatePreference::where('user_id', $userId)->exists();

        // Roommate matches count (placeholder for future matching algorithm)
        // TODO: Implement actual matching algorithm based on preferences
        $roommateMatchesCount = $roommatePrefExists ? 3 : 0;

        // Saved listings count - safely check if table exists
        $savedListingsCount = 0;
        if (Schema::hasTable('saved_listings')) {
            $savedListingsCount = DB::table('saved_listings')
                ->where('user_id', $userId)
                ->count();
        }

        return view('user.dashboard', compact(
            'featuredListings',
            'activeBookingsCount',
            'confirmedBookingsCount',
            'cancelledBookingsCount',
            'recentBookings',
            'recentReviews',
            'roommatePrefExists',
            'roommateMatchesCount',
            'savedListingsCount'
        ));
    }
}
