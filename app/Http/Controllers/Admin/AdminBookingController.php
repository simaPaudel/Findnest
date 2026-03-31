<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class AdminBookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['property', 'user'])
            ->latest()
            ->paginate(10);

        return view('admin.bookings.index', compact('bookings'));
    }
}
