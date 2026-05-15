<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use App\Models\Property;
use App\Models\Review;
use App\Models\Room;
use App\Models\TrustPoint;
use App\Services\BookingService;
use App\Services\NotificationService;
use App\Services\RoomAvailabilityService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserBookingController extends Controller
{
    protected BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    // ==================== USER BOOKING VIEWS ====================

    /**
     * Display user's bookings.
     */
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with(['property', 'room', 'payments'])
            ->latest()
            ->get();

        $pendingBookings = $bookings->filter(function (Booking $booking) {
            return ! $booking->isCancelled()
                && ! $booking->isRejected()
                && (
                    ! $booking->hasSuccessfulPayment()
                    || (float) $booking->getTotalPaid() <= 0
                );
        })->unique(function (Booking $booking) {
            return $booking->property_id . ':' . ($booking->room_id ?? 'full-property');
        })->values();

        $confirmedPaidBookings = $bookings->filter(function (Booking $booking) {
            return ! $booking->isCancelled()
                && ! $booking->isRejected()
                && $booking->hasSuccessfulPayment()
                && (float) $booking->getTotalPaid() > 0;
        });

        $cancelledBookings = $bookings->filter(fn (Booking $booking) => $booking->isCancelled());

        $activeTab = in_array(request('tab'), ['pending', 'paid', 'cancelled'], true)
            ? request('tab')
            : 'pending';

        $activeBookings = match ($activeTab) {
            'paid' => $confirmedPaidBookings,
            'cancelled' => $cancelledBookings,
            default => $pendingBookings,
        };

        return view('user.bookings.index', compact(
            'bookings',
            'pendingBookings',
            'confirmedPaidBookings',
            'cancelledBookings',
            'activeBookings',
            'activeTab'
        ));
    }

    /**
     * Show booking details.
     */
    public function show(Booking $booking)
    {
        // Verify booking belongs to authenticated user
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $booking->load(['property.owner', 'room', 'payments', 'trustPoints']);
        $existingReview = Review::where('user_id', Auth::id())
            ->where('booking_id', $booking->id)
            ->first();

        $isFeedbackAvailable = $booking->isStayCompletedForFeedback();
        $canReviewBooking = $isFeedbackAvailable && ! $existingReview;
        $ownerTrustGiven = TrustPoint::where('booking_id', $booking->id)
            ->where('giver_id', Auth::id())
            ->where('receiver_id', $booking->property?->owner_id)
            ->exists();
        $canGiveOwnerTrustPoint = $isFeedbackAvailable
            && $booking->property?->owner_id
            && (int) $booking->property->owner_id !== (int) Auth::id()
            && ! $ownerTrustGiven;

        return view('user.bookings.show', compact(
            'booking',
            'existingReview',
            'canReviewBooking',
            'isFeedbackAvailable',
            'ownerTrustGiven',
            'canGiveOwnerTrustPoint'
        ));
    }

    /**
     * Show booking request/creation form.
     */
    public function request(Request $request, Property $property, RoomAvailabilityService $roomAvailabilityService)
    {
        // Verify property is approved and available
        if ($property->status !== 'approved') {
            abort(404, 'Property not found');
        }

        $roomAvailabilityService->decorateProperty($property);

        $rooms = collect();
        $selectedRoom = null;

        // Get available rooms if property supports room rentals
        if ($property->canRentRooms()) {
            $rooms = $property->rooms()
                ->with(['images' => function ($query) {
                    $query->ordered();
                }])
                ->withCount([
                    'bookings as active_confirmed_bookings_count' => function ($query) {
                        $query->where('status', 'confirmed');
                    }
                ])
                ->orderBy('price')
                ->get();

            $rooms = $roomAvailabilityService->decorateCollection($rooms)
                ->filter(fn (Room $room) => (bool) $room->is_bookable)
                ->values();

            $selectedRoomId = (int) $request->query('room');
            if ($selectedRoomId > 0) {
                $selectedRoom = $rooms->firstWhere('id', $selectedRoomId);

                if (!$selectedRoom) {
                    return redirect()
                        ->route('listings.show', $property)
                        ->with('error', 'This room is already booked right now.');
                }
            }

            if (!$selectedRoom && $rooms->isNotEmpty()) {
                $selectedRoom = $rooms->first();
            }

            if ($rooms->isEmpty()) {
                return redirect()
                    ->route('listings.show', $property)
                    ->with('error', 'All rooms in this property are already booked right now.');
            }
        } elseif (!$property->is_property_bookable) {
            return redirect()
                ->route('listings.show', $property)
                ->with('error', 'This property is already booked right now.');
        }

        return view('user.bookings.request', compact('property', 'rooms', 'selectedRoom'));
    }

    /**
     * Store a new booking.
     */
    public function create(StoreBookingRequest $request, RoomAvailabilityService $roomAvailabilityService)
    {
        try {
            $data = $request->validated();

            $property = Property::where('status', 'approved')
                ->with([
                    'rooms' => function ($query) {
                        $query->withCount([
                            'bookings as active_confirmed_bookings_count' => function ($bookingQuery) {
                                $bookingQuery->where('status', 'confirmed');
                            }
                        ]);
                    }
                ])
                ->findOrFail($data['property_id']);

            $checkInDate = Carbon::parse($data['check_in_date'])->startOfDay();
            $checkOutDate = $checkInDate->copy()->addMonth();

            if ($property->canRentRooms()) {
                $selectedRoom = $property->rooms
                    ->firstWhere('id', $data['room_id'] ?? null);

                if (!$selectedRoom) {
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with('error', 'Please select an available room.');
                }

                $roomAvailabilityService->decorate($selectedRoom);

                if (!(bool) $selectedRoom->is_bookable) {
                    return redirect()
                        ->route('listings.show', $property)
                        ->with('error', 'This room is already booked right now.');
                }

                $monthlyRent = (float) $selectedRoom->price;
            } else {
                $hasActiveFullPropertyBooking = $property->bookings()
                    ->whereNull('room_id')
                    ->where('status', 'confirmed')
                    ->exists();

                if ($hasActiveFullPropertyBooking) {
                    return redirect()
                        ->route('listings.show', $property)
                        ->with('error', 'This property is already booked right now.');
                }

                $monthlyRent = (float) $property->rent_price;
            }

            $existingActiveBooking = Booking::where('user_id', Auth::id())
                ->where('property_id', $property->id)
                ->whereIn('status', ['pending', 'confirmed', 'completed'])
                ->when(
                    $data['room_id'] !== null,
                    fn ($query) => $query->where('room_id', $data['room_id']),
                    fn ($query) => $query->whereNull('room_id')
                )
                ->latest()
                ->first();

            if ($existingActiveBooking) {
                return redirect()
                    ->route('user.bookings.show', $existingActiveBooking)
                    ->with('error', 'You already have an active booking for this property.');
            }

            $data['check_in_date'] = $checkInDate->toDateString();
            $data['check_out_date'] = $checkOutDate->toDateString();
            $data['total_rent'] = round($monthlyRent, 2);
            $data['advance_payment'] = round($monthlyRent * 0.20, 2);
            $data['security_deposit'] = 0;

            // Create the booking using service
            $booking = $this->bookingService->createBooking($data);

            if ($property->owner_id && (int) $property->owner_id !== (int) Auth::id()) {
                try {
                    NotificationService::sendNotification(
                        (int) $property->owner_id,
                        'booking',
                        'New booking request',
                        'A new booking request has been submitted for your property.',
                        route('owner.bookings.index')
                    );
                } catch (\Throwable $notificationError) {
                    // Notification failures must not block booking creation.
                }
            }

            return redirect()
                ->route('user.bookings.bill', $booking)
                ->with('success', 'Booking created. Review the invoice and proceed to payment.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Show booking bill/invoice.
     */
    public function bill(Booking $booking)
    {
        // Verify booking belongs to authenticated user
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $booking->load(['property', 'room', 'payments']);

        return view('user.bookings.bill', $this->buildInvoiceData($booking));
    }

    /**
     * Download booking invoice as PDF after successful payment.
     */
    public function downloadInvoice(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $booking->load(['property', 'room', 'payments']);

        if (!$booking->hasSuccessfulPayment()) {
            return redirect()
                ->route('user.bookings.bill', $booking)
                ->with('error', 'Complete the advance payment first to download the invoice.');
        }

        $pdf = Pdf::loadView('user.bookings.pdf', $this->buildInvoiceData($booking))
            ->setPaper('a4');

        return $pdf->download('findnest-invoice-' . $booking->id . '.pdf');
    }

    /**
     * Edit booking details (only updatable fields).
     */
    public function edit(Booking $booking)
    {
        // Verify booking belongs to authenticated user
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Can only edit pending bookings
        if (!$booking->isPending()) {
            return redirect()->back()->with('error', 'Can only edit pending bookings.');
        }

        $booking->load(['property', 'room']);

        return view('user.bookings.edit', compact('booking'));
    }

    /**
     * Update booking details.
     */
    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        // Verify booking belongs to authenticated user
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Can only edit pending bookings
        if (!$booking->isPending()) {
            return redirect()->back()->with('error', 'Can only edit pending bookings.');
        }

        try {
            $data = $request->validated();
            $this->bookingService->updateBooking($booking, $data);

            return redirect()
                ->route('user.bookings.show', $booking)
                ->with('success', 'Booking updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Cancel a booking.
     */
    public function cancel(Booking $booking)
    {
        // Verify booking belongs to authenticated user
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        try {
            $this->bookingService->cancelBooking($booking);

            return redirect()
                ->route('user.bookings.index')
                ->with('success', 'Booking cancelled successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Store or update a property review for a user's booking.
     */
    public function storeReview(StoreReviewRequest $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if (! $booking->isStayCompletedForFeedback()) {
            return redirect()
                ->route('user.bookings.show', $booking)
                ->with('error', 'You can review this property only after the paid stay period is completed.');
        }

        $alreadyReviewed = Review::where('booking_id', $booking->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($alreadyReviewed) {
            return redirect()
                ->route('user.bookings.show', $booking)
                ->with('error', 'Review already submitted for this booking.');
        }

        Review::create([
            'user_id' => Auth::id(),
            'property_id' => $booking->property_id,
            'booking_id' => $booking->id,
            'review_type' => 'property',
            'rating' => $request->validated('rating'),
            'review_text' => $request->validated('review_text'),
            'is_verified' => true,
            'is_approved' => false,
        ]);

        return redirect()
            ->route('user.bookings.show', $booking)
            ->with('success', 'Review submitted successfully. It will appear after admin approval.');
    }

    public function storeTrustPoint(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $booking->load('property.owner');
        $receiver = $booking->property?->owner;

        if (! $receiver || (int) $receiver->id === (int) Auth::id()) {
            return redirect()
                ->route('user.bookings.show', $booking)
                ->with('error', 'Trust point cannot be given to this account.');
        }

        if (! $booking->isStayCompletedForFeedback()) {
            return redirect()
                ->route('user.bookings.show', $booking)
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
            ->route('user.bookings.show', $booking)
            ->with($created ? 'success' : 'error', $created ? 'Trust point given to the owner.' : 'Trust point already given for this booking.');
    }

    /**
     * Check availability for dates.
     */
    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|integer|exists:properties,id',
            'room_id' => 'nullable|integer|exists:rooms,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
        ]);

        $isAvailable = $this->bookingService->isAvailable(
            $validated['property_id'],
            $validated['check_in_date'],
            $validated['check_out_date'],
            $validated['room_id'] ?? null
        );

        return response()->json([
            'available' => $isAvailable,
            'message' => $isAvailable ? 'Dates are available' : 'Dates are not available',
        ]);
    }

    /**
     * Get available dates for a property in calendar format.
     */
    public function getAvailableDates(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|integer|exists:properties,id',
            'room_id' => 'nullable|integer|exists:rooms,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2024',
        ]);

        $availableDates = $this->bookingService->getAvailableDates(
            $validated['property_id'],
            str_pad($validated['month'], 2, '0', STR_PAD_LEFT),
            $validated['year'],
            $validated['room_id'] ?? null
        );

        return response()->json([
            'dates' => $availableDates,
        ]);
    }

    /**
     * Build shared invoice data for screen and PDF views.
     */
    protected function buildInvoiceData(Booking $booking): array
    {
        return [
            'booking' => $booking,
            'durationDays' => $booking->getDurationInDays(),
            'durationMonths' => $booking->getDurationInMonths(),
            'totalPaid' => $booking->getTotalPaid(),
            'amountPending' => $booking->getAmountPending(),
            'paymentProgress' => $booking->getPaymentProgress(),
            'bookableName' => $booking->getBookableName(),
            'dueNow' => round((float) $booking->total_rent * 0.20, 2),
            'remainingBalance' => max((float) $booking->total_rent - $booking->getTotalPaid(), 0),
        ];
    }
}
