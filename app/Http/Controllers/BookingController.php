<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Property;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['property', 'room'])
            ->where('student_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function create()
    {
        $properties = Property::where('status', 'approved')->get();
        return view('bookings.create', compact('properties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'room_id' => 'nullable|exists:rooms,id',
            'check_in_date' => 'required|date',
            'duration_months' => 'required|integer|min:1'
        ]);

        if ($request->room_id) {
            $room = Room::findOrFail($request->room_id);
            if (!$room->availability || $room->current_occupancy >= $room->capacity) {
                return back()->withErrors([
                    'room_id' => 'Selected room is not available'
                ]);
            }
        }

        $property = Property::findOrFail($request->property_id);

        Booking::create([
            'student_id' => Auth::id(),
            'property_id' => $request->property_id,
            'room_id' => $request->room_id,
            'check_in_date' => $request->check_in_date,
            'duration_months' => $request->duration_months,
            'total_rent' => $property->rent_price * $request->duration_months,
            'special_requests' => $request->special_requests
        ]);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking created successfully!');
    }

    public function show($id)
    {
        $booking = Booking::with(['property', 'room', 'payments'])
            ->where('student_id', Auth::id())
            ->findOrFail($id);

        return view('bookings.show', compact('booking'));
    }
}