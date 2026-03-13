<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;

class OwnerBookingController extends Controller
{
    /**
     * Display a listing of bookings for owner's properties.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $owner = Auth::user();

        // Get owner's property IDs
        $propertyIds = Property::where('owner_id', $owner->id)->pluck('id');

        // Get bookings for owner's properties with relationships
        $bookings = Booking::whereIn('property_id', $propertyIds)
            ->with(['property', 'user'])
            ->latest()
            ->paginate(15);

        return view('owner.bookings.index', compact('bookings'));
    }

    /**
     * Accept a booking request.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function accept(Booking $booking)
    {
        // Authorization: ensure booking is for owner's property
        if ($booking->property->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Update booking status
        $booking->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        return redirect()
            ->route('owner.bookings.index')
            ->with('success', 'Booking request accepted successfully!');
    }

    /**
     * Reject a booking request.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Booking $booking)
    {
        // Authorization: ensure booking is for owner's property
        if ($booking->property->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Update booking status
        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return redirect()
            ->route('owner.bookings.index')
            ->with('success', 'Booking request rejected.');
    }
}
