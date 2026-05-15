<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Property;
use App\Models\TrustPoint;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OwnerBookingController extends Controller
{
    /**
     
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
            ->with(['property', 'user', 'payments', 'trustPoints'])
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

        $originalStatus = $booking->status;

        // Update booking status
        $booking->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        if ($originalStatus !== 'confirmed' && $booking->user_id) {
            try {
                NotificationService::sendNotification(
                    (int) $booking->user_id,
                    'booking',
                    'Booking request approved',
                    'Your booking request has been approved.',
                    route('user.bookings.show', $booking)
                );
            } catch (\Throwable $notificationError) {
                // Notification failures must not block booking updates.
            }
        }

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

        $originalStatus = $booking->status;

        // Update booking status
        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        if ($originalStatus !== 'cancelled' && $booking->user_id) {
            try {
                NotificationService::sendNotification(
                    (int) $booking->user_id,
                    'booking',
                    'Booking request rejected',
                    'Your booking request has been rejected.',
                    route('user.bookings.show', $booking)
                );
            } catch (\Throwable $notificationError) {
                // Notification failures must not block booking updates.
            }
        }

        return redirect()
            ->route('owner.bookings.index')
            ->with('success', 'Booking request rejected.');
    }

    public function storeTrustPoint(Booking $booking)
    {
        $booking->load(['property', 'user']);

        if ($booking->property->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $receiver = $booking->user;

        if (! $receiver || (int) $receiver->id === (int) Auth::id()) {
            return redirect()
                ->route('owner.bookings.index')
                ->with('error', 'Trust point cannot be given to this account.');
        }

        if (! $booking->isStayCompletedForFeedback()) {
            return redirect()
                ->route('owner.bookings.index')
                ->with('error', 'Trust point is available only after the paid stay period is completed.');
        }

        try {
            $created = DB::transaction(function () use ($booking, $receiver): bool {
                $trustPoint = TrustPoint::firstOrCreate([
                    'booking_id' => $booking->id,
                    'giver_id' => Auth::id(),
                    'receiver_id' => $receiver->id,
                ]);

                if (! $trustPoint->wasRecentlyCreated) {
                    return false;
                }

                $receiver->increment('trust_points');

                return true;
            });
        } catch (\Throwable $e) {
            $created = false;
        }

        return redirect()
            ->route('owner.bookings.index')
            ->with($created ? 'success' : 'error', $created ? 'Trust point given to the user.' : 'Trust point already given for this booking.');
    }
}
