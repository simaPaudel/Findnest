<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Property;
use App\Models\Report;
use App\Models\User;
use App\Services\BookingService;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::with([
            'property.owner',
            'property.images',
            'property.rooms.images',
            'room.images',
            'user',
            'payments',
        ])
            ->when($request->filled('user'), function ($query) use ($request) {
                $query->where('user_id', $request->integer('user'));
            })
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load([
            'property.owner',
            'property.images',
            'property.rooms.images',
            'room.images',
            'user',
            'payments',
        ]);

        $relatedReports = Report::query()
            ->with(['reporter', 'reviewedByUser'])
            ->where(function ($query) use ($booking) {
                $query->where(function ($reportQuery) use ($booking) {
                    $reportQuery->where('reportable_type', Property::class)
                        ->where('reportable_id', $booking->property_id);
                })->orWhere(function ($reportQuery) use ($booking) {
                    $reportQuery->where('reportable_type', User::class)
                        ->where('reportable_id', $booking->user_id);
                });
            })
            ->recent()
            ->limit(6)
            ->get();

        $latestPayment = $booking->payments->sortByDesc('created_at')->first();
        $paymentState = $booking->hasSuccessfulPayment()
            ? 'paid'
            : ($latestPayment?->payment_status ?? 'unpaid');

        $relatedDisputesCount = $relatedReports->count();

        return view('admin.bookings.show', compact(
            'booking',
            'relatedReports',
            'latestPayment',
            'paymentState',
            'relatedDisputesCount'
        ));
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
