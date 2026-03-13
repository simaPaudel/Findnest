<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class OwnerDashboardController extends Controller
{
    /**
     * Display the owner dashboard with statistics.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $owner = Auth::user();

        // Get owner's property IDs
        $propertyIds = Property::where('owner_id', $owner->id)->pluck('id');

        // Total listings count
        $totalListings = Property::where('owner_id', $owner->id)->count();

        // Active (approved) listings count
        $activeListings = Property::where('owner_id', $owner->id)
            ->where('status', 'approved')
            ->count();

        // Pending booking requests for owner's properties
        $pendingBookingRequests = Booking::whereIn('property_id', $propertyIds)
            ->where('status', 'pending')
            ->count();

        // Reviews for owner's properties
        $reviews = Review::whereIn('property_id', $propertyIds)->get();
        $reviewsCount = $reviews->count();
        $avgRating = $reviews->avg('rating') ?? 0;

        // Recent bookings (latest 5) with relationships
        $recentBookings = Booking::whereIn('property_id', $propertyIds)
            ->with(['user', 'property'])
            ->latest()
            ->limit(5)
            ->get();

        return view('owner.dashboard', compact(
            'totalListings',
            'activeListings',
            'pendingBookingRequests',
            'reviewsCount',
            'avgRating',
            'recentBookings'
        ));
    }
}
