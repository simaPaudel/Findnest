<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingService;

class AdminBookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['property', 'user', 'room', 'payments'])
            ->latest()
            ->paginate(10);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function release(Booking $booking, BookingService $bookingService)
    {
        if (! $booking->hasSuccessfulPayment()) {
            return redirect()
                ->route('admin.bookings.index')
                ->with('error', 'Only paid bookings can be released.');
        }

        try {
            $bookingService->completeBooking($booking);

            return redirect()
                ->route('admin.bookings.index')
                ->with('success', 'Booking released successfully. Availability has been updated.');
        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.bookings.index')
                ->with('error', $e->getMessage());
        }
    }
}
