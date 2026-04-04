<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserBookingController extends Controller
{
    /**
     * Display user's bookings.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with('property')
            ->latest()
            ->paginate(10);

        return view('user.bookings.index', compact('bookings'));
    }

    /**
     * Show booking request form
     */
    public function request(Property $property)
    {
        // Verify property is approved
        if ($property->status !== 'approved') {
            abort(404);
        }

        return view('user.bookings.request', compact('property'));
    }

    /**
     * Create a new booking and show bill
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'check_in_date' => 'required|date|after:today',
        ]);

        $property = Property::findOrFail($validated['property_id']);

        // Verify property is approved
        if ($property->status !== 'approved') {
            abort(404);
        }

        // Default duration is 1 month
        $durationMonths = 1;

        // Parse check-in date
        $checkInDate = \Carbon\Carbon::parse($validated['check_in_date']);

        // Calculate total rent (1 month default)
        $totalRent = $property->rent_price * $durationMonths;

        // Create booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'property_id' => $property->id,
            'room_id' => null,
            'status' => 'pending',
            'check_in_date' => $checkInDate,
            'check_out_date' => null,
            'duration_months' => $durationMonths,
            'advance_payment' => $totalRent * 0.20,
            'security_deposit' => 0,
            'total_rent' => $totalRent,
            'payment_status' => 'unpaid',
            'special_requests' => null,
        ]);

        return redirect()->route('user.bookings.bill', $booking->id)
            ->with('success', 'Booking created. Review the invoice and proceed to payment.');
    }

    /**
     * Show booking bill/invoice
     */
    public function bill(Booking $booking)
    {
        // Verify booking belongs to authenticated user
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Load relationships
        $booking->load('property');

        return view('user.bookings.bill', compact('booking'));
    }

    /**
     * Cancel a booking.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Booking $booking)
    {
        // Verify the booking belongs to the authenticated user
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Allow cancellation for pending or confirmed bookings only
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return redirect()->back()->with('error', 'Only pending or confirmed bookings can be cancelled.');
        }

        // Update booking status to cancelled with timestamp
        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now()
        ]);

        return redirect()->route('user.bookings.index')
            ->with('success', 'Booking cancelled successfully.');
    }
}
