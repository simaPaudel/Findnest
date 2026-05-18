<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\User;
use App\Services\RoomAvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request, RoomAvailabilityService $roomAvailabilityService)
    {
        try {
            // Redirect authenticated users to their respective dashboards
            if (Auth::check()) {
                $user = Auth::user();

                switch ($user->role) {
                    case User::ROLE_ADMIN:
                        return redirect()->route('admin.dashboard');
                    case User::ROLE_OWNER:
                        return redirect()->route('owner.dashboard');
                    case User::ROLE_USER:
                    default:
                        return redirect()->route('listings.index');
                }
            }

            $featuredListings = Property::verified()
                ->withApprovedReviewStats()
                ->with([
                    'images' => function ($query) {
                        $query->ordered();
                    },
                    'rooms' => function ($query) {
                        $query->with(['images' => function ($imageQuery) {
                            $imageQuery->ordered();
                        }])->withCount([
                            'bookings as active_confirmed_bookings_count' => function ($bookingQuery) {
                                $bookingQuery->where('status', 'confirmed');
                            }
                        ])->orderBy('price');
                    },
                ])
                ->withMin([
                    'rooms as min_room_price' => function ($query) {
                        $query->where('availability', true)
                            ->whereDoesntHave('bookings', function ($bookingQuery) {
                                $bookingQuery->where('status', 'confirmed');
                            });
                    }
                ], 'price')
                ->withMax([
                    'rooms as max_room_price' => function ($query) {
                        $query->where('availability', true)
                            ->whereDoesntHave('bookings', function ($bookingQuery) {
                                $bookingQuery->where('status', 'confirmed');
                            });
                    }
                ], 'price')
                ->withCount([
                    'rooms as available_rooms_count' => function ($query) {
                        $query->where('availability', true)
                            ->whereDoesntHave('bookings', function ($bookingQuery) {
                                $bookingQuery->where('status', 'confirmed');
                            });
                    }
                ])
                ->when($request->filled('q'), function ($query) use ($request) {
                    $q = $request->q;
                    $query->where(function ($inner) use ($q) {
                        $inner->where('city', 'like', "%{$q}%")
                            ->orWhere('location', 'like', "%{$q}%")
                            ->orWhere('address', 'like', "%{$q}%")
                            ->orWhere('title', 'like', "%{$q}%");
                    });
                })
                ->when($request->filled('max_price') && is_numeric($request->max_price), function ($query) use ($request) {
                    $maxPrice = (float) $request->max_price;

                    $query->where(function ($priceQuery) use ($maxPrice) {
                        $priceQuery->where(function ($fullPropertyQuery) use ($maxPrice) {
                            $fullPropertyQuery->where('rental_mode', 'full_property')
                                ->where('rent_price', '<=', $maxPrice);
                        })->orWhere(function ($perRoomQuery) use ($maxPrice) {
                            $perRoomQuery->where('rental_mode', 'per_room')
                                ->whereHas('rooms', function ($roomQuery) use ($maxPrice) {
                                    $roomQuery->where('availability', true)
                                        ->whereDoesntHave('bookings', function ($bookingQuery) {
                                            $bookingQuery->where('status', 'confirmed');
                                        })
                                        ->where('price', '<=', $maxPrice);
                                });
                        });
                    });
                })
                ->when($request->filled('property_type'), function ($query) use ($request) {
                    $query->where('property_type', $request->property_type);
                })
                ->latest()
                ->limit(8)
                ->get();

            $featuredListings = $roomAvailabilityService->decoratePropertyCollection($featuredListings);

            // Fetch stats
            $stats = [
                'totalListings' => Property::where('status', 'approved')->count(),
                'verifiedListings' => Property::verified()->count(),
                'activeOwners' => User::where('role', 'owner')->count(),
            ];

            return view('welcome', compact('featuredListings', 'stats'));
        } catch (\Throwable $e) {
            report($e);

            return view('welcome', [
                'featuredListings' => collect(),
                'stats' => [
                    'totalListings' => 0,
                    'verifiedListings' => 0,
                    'activeOwners' => 0,
                ],
            ]);
        }
    }
}
