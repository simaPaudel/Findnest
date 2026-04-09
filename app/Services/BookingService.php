<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Property;
use App\Models\Room;
use Illuminate\Support\Facades\DB;

class BookingService
{
    /**
     * Create a new booking.
     */
    public function createBooking(array $data): Booking
    {
        DB::beginTransaction();

        try {
            // Validate dates don't conflict with existing bookings
            $this->validateBookingDates(
                $data['property_id'],
                $data['room_id'] ?? null,
                $data['check_in_date'],
                $data['check_out_date']
            );

            // Create the booking
            $booking = Booking::create($data);

            DB::commit();

            return $booking;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Update an existing booking.
     */
    public function updateBooking(Booking $booking, array $data): Booking
    {
        DB::beginTransaction();

        try {
            $booking->update($data);

            DB::commit();

            return $booking;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Confirm a booking (changes status from pending to confirmed).
     */
    public function confirmBooking(Booking $booking): Booking
    {
        if (!$booking->isPending()) {
            throw new \LogicException('Only pending bookings can be confirmed.');
        }

        $booking->confirm();

        return $booking;
    }

    /**
     * Reject a booking with reason.
     */
    public function rejectBooking(Booking $booking, string $reason = null): Booking
    {
        if ($booking->isCompleted() || $booking->isCancelled()) {
            throw new \LogicException('Cannot reject a completed or cancelled booking.');
        }

        $booking->reject($reason);

        return $booking;
    }

    /**
     * Cancel a booking.
     */
    public function cancelBooking(Booking $booking): Booking
    {
        if ($booking->isCompleted()) {
            throw new \LogicException('Cannot cancel a completed booking.');
        }

        if ($this->shouldReleaseRoomInventory($booking)) {
            DB::transaction(function () use ($booking) {
                $freshBooking = Booking::lockForUpdate()->findOrFail($booking->id);
                $this->releaseInventory($freshBooking);
                $freshBooking->cancel();
                $booking->refresh();
            });

            return $booking;
        }

        $booking->cancel();

        return $booking;
    }

    /**
     * Mark a booking as completed.
     */
    public function completeBooking(Booking $booking): Booking
    {
        if (!$booking->isConfirmed()) {
            throw new \LogicException('Only confirmed bookings can be completed.');
        }

        DB::transaction(function () use ($booking) {
            $freshBooking = Booking::lockForUpdate()->findOrFail($booking->id);

            if ($this->shouldReleaseRoomInventory($freshBooking)) {
                $this->releaseInventory($freshBooking);
            }

            $freshBooking->complete();
            $booking->refresh();
        });

        return $booking;
    }

    /**
     * Reserve room inventory for a confirmed paid booking.
     */
    public function reserveInventory(Booking $booking): void
    {
        if (!$booking->isRoomSpecific()) {
            return;
        }

        $room = Room::lockForUpdate()->find($booking->room_id);

        if (!$room) {
            throw new \LogicException('Selected room could not be found.');
        }

        if (!$room->availability) {
            throw new \LogicException('This room is no longer available for booking.');
        }

        $hasAnotherConfirmedBooking = $room->bookings()
            ->where('status', 'confirmed')
            ->where('id', '!=', $booking->id)
            ->exists();

        if ($hasAnotherConfirmedBooking) {
            throw new \LogicException('This room is already booked.');
        }

        $room->update([
            'current_occupancy' => 0,
            'availability' => false,
        ]);
    }

    /**
     * Release room inventory after a completed stay or released booking.
     */
    public function releaseInventory(Booking $booking): void
    {
        if (!$booking->isRoomSpecific()) {
            return;
        }

        $room = Room::lockForUpdate()->find($booking->room_id);

        if (!$room) {
            return;
        }

        $room->update([
            'current_occupancy' => 0,
            'availability' => true,
        ]);
    }

    /**
     * Validate that booking dates don't conflict with existing bookings.
     */
    public function validateBookingDates(
        int $propertyId,
        ?int $roomId,
        $checkInDate,
        $checkOutDate
    ): void {
        $query = Booking::where('property_id', $propertyId)
            ->where('status', 'confirmed')
            ->where(function ($q) use ($checkInDate, $checkOutDate) {
                $q->whereBetween('check_in_date', [$checkInDate, $checkOutDate])
                    ->orWhereBetween('check_out_date', [$checkInDate, $checkOutDate])
                    ->orWhere(function ($subQ) use ($checkInDate, $checkOutDate) {
                        $subQ->where('check_in_date', '<=', $checkInDate)
                            ->where('check_out_date', '>=', $checkOutDate);
                    });
            });

        // If it's a room-specific booking, check that room
        if ($roomId) {
            $query->where(function ($q) use ($roomId) {
                $q->where('room_id', $roomId)
                    ->orWhereNull('room_id'); // Full property bookings conflict with all room bookings
            });
        } else {
            // Full property booking conflicts with room bookings
            $query->orWhere('room_id', null);
        }

        if ($query->exists()) {
            throw new \Exception('Booking dates conflict with existing reservations.');
        }
    }

    /**
     * Check availability for a property and date range.
     */
    public function isAvailable(
        int $propertyId,
        $checkInDate,
        $checkOutDate,
        ?int $roomId = null
    ): bool {
        try {
            $this->validateBookingDates($propertyId, $roomId, $checkInDate, $checkOutDate);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get available dates for a property in a given month.
     */
    public function getAvailableDates(int $propertyId, string $month, string $year, ?int $roomId = null): array
    {
        $startDate = \Carbon\Carbon::createFromFormat('Y-m', "{$year}-{$month}")->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $bookedDates = Booking::where('property_id', $propertyId)
            ->where('status', 'confirmed')
            ->whereBetween('check_in_date', [$startDate, $endDate])
            ->orWhereBetween('check_out_date', [$startDate, $endDate])
            ->when($roomId, function ($query) use ($roomId) {
                return $query->where(function ($q) use ($roomId) {
                    $q->where('room_id', $roomId)->orWhereNull('room_id');
                });
            })
            ->get(['check_in_date', 'check_out_date']);

        $allDates = [];
        $current = $startDate->copy();

        while ($current->lte($endDate)) {
            $allDates[$current->format('Y-m-d')] = false;
            $current->addDay();
        }

        // Mark booked dates
        foreach ($bookedDates as $booking) {
            $date = $booking->check_in_date->copy();
            while ($date->lt($booking->check_out_date)) {
                $dateKey = $date->format('Y-m-d');
                if (array_key_exists($dateKey, $allDates)) {
                    $allDates[$dateKey] = true;
                }
                $date->addDay();
            }
        }

        return $allDates;
    }

    /**
     * Calculate total rent based on duration and rate.
     */
    public function calculateRent(int $durationDays, float $dailyRate): float
    {
        return (float) ($durationDays * $dailyRate);
    }

    /**
     * Get bookings for a property with their details.
     */
    public function getPropertyBookings(Property $property, ?string $status = null)
    {
        $query = $property->bookings()->with(['user', 'room']);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest()->get();
    }

    /**
     * Get room bookings with their details.
     */
    public function getRoomBookings(Room $room, ?string $status = null)
    {
        $query = $room->bookings()->with(['user', 'property']);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest()->get();
    }

    /**
     * Check if a property/room has conflicting bookings.
     */
    public function hasConflictingBookings(
        int $propertyId,
        ?int $roomId = null
    ): bool {
        $query = Booking::where('property_id', $propertyId)
            ->whereIn('status', ['pending', 'confirmed']);

        if ($roomId) {
            $query->where('room_id', $roomId);
        }

        return $query->exists();
    }

    /**
     * Get occupancy statistics for a property.
     */
    public function getOccupancyStats(Property $property, int $days = 30): array
    {
        $totalDays = $days;

        $bookedDays = Booking::where('property_id', $property->id)
            ->where('status', 'confirmed')
            ->where('check_in_date', '>=', now()->subDays($days))
            ->get()
            ->sum(function ($booking) {
                return $booking->check_in_date->diffInDays($booking->check_out_date);
            });

        return [
            'total_days' => $totalDays,
            'booked_days' => $bookedDays,
            'available_days' => $totalDays - $bookedDays,
            'occupancy_rate' => $totalDays > 0 ? round(($bookedDays / $totalDays) * 100, 2) : 0,
        ];
    }

    /**
     * Get revenue statistics for a property.
     */
    public function getRevenueStats(Property $property, int $months = 6): array
    {
        $startDate = now()->subMonths($months)->startOfMonth();

        $bookings = $property->bookings()
            ->where('status', 'confirmed')
            ->where('check_in_date', '>=', $startDate)
            ->get();

        $totalRevenue = $bookings->sum('total_rent');
        $totalPaid = $bookings->sum(function ($booking) {
            return $booking->getTotalPaid();
        });

        return [
            'total_bookings' => $bookings->count(),
            'total_revenue' => (float) $totalRevenue,
            'total_paid' => (float) $totalPaid,
            'pending_amount' => (float) ($totalRevenue - $totalPaid),
            'average_booking' => $bookings->count() > 0 ? round($totalRevenue / $bookings->count(), 2) : 0,
        ];
    }

    /**
     * Determine whether room inventory should be released for this booking.
     */
    protected function shouldReleaseRoomInventory(Booking $booking): bool
    {
        return $booking->isRoomSpecific() && $booking->hasSuccessfulPayment();
    }
}
