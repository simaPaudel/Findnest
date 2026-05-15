<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Services\RoomAvailabilityService;
use Illuminate\Http\Request;

class ListingsController extends Controller
{
    /**
     * Display a listing of properties.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request, RoomAvailabilityService $roomAvailabilityService)
    {
        // Base query for approved properties
        $query = Property::where('status', 'approved')
            ->withApprovedReviewStats()
            ->with([
                'images' => function ($query) {
                    $query->ordered();
                },
                'rooms' => function ($query) {
                    $query->select([
                        'id',
                        'property_id',
                        'room_name',
                        'capacity',
                        'current_occupancy',
                        'price',
                        'availability',
                    ])->withCount([
                        'bookings as active_confirmed_bookings_count' => function ($bookingQuery) {
                            $bookingQuery->where('status', 'confirmed');
                        }
                    ])->orderBy('price');
                }
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
                },
                'bookings as active_full_property_bookings_count' => function ($query) {
                    $query->whereNull('room_id')
                        ->where('status', 'confirmed');
                }
            ]);

        // Search by location keyword (city, location, address, or title)
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qry) use ($q) {
                $qry->where('city', 'like', "%$q%")
                    ->orWhere('location', 'like', "%$q%")
                    ->orWhere('address', 'like', "%$q%")
                    ->orWhere('title', 'like', "%$q%");
            });
        }

        // Filter by max price
        if ($request->filled('max_price') && is_numeric($request->max_price)) {
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
        }

        // Filter by property type (optional)
        if ($request->filled('property_type')) {
            $query->where('property_type', $request->property_type);
        }

        // Filter by city (optional)
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Sorting (optional)
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderByRaw('CASE WHEN rental_mode = ? THEN COALESCE(min_room_price, rent_price) ELSE rent_price END ASC', ['per_room']);
                    break;
                case 'price_high':
                    $query->orderByRaw('CASE WHEN rental_mode = ? THEN COALESCE(max_room_price, rent_price) ELSE rent_price END DESC', ['per_room']);
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        // Paginate results
        $properties = $query->paginate(12)->appends($request->query());
        $properties->setCollection(
            $roomAvailabilityService->decoratePropertyCollection($properties->getCollection())
        );

        return view('listings.index', compact('properties'));
    }

    /**
     * Display the specified property.
     *
     * @param  \App\Models\Property  $property
     * @return \Illuminate\View\View
     */
    public function show(Property $property, RoomAvailabilityService $roomAvailabilityService)
    {
        // Only show approved properties
        if ($property->status !== 'approved') {
            abort(404);
        }

        // Load property details, images, amenities, and room options
        $property->load([
            'owner',
            'orderedImages',
            'amenities',
            'rooms' => function ($query) {
                $query->with(['images' => function ($imageQuery) {
                    $imageQuery->ordered();
                }])->withCount([
                    'bookings as active_confirmed_bookings_count' => function ($bookingQuery) {
                        $bookingQuery->where('status', 'confirmed');
                    }
                ])->orderByDesc('availability')->orderBy('price');
            },
        ]);
        $property->loadCount([
            'bookings as active_full_property_bookings_count' => function ($query) {
                $query->whereNull('room_id')
                    ->where('status', 'confirmed');
            }
        ]);

        $property->setRelation(
            'rooms',
            $roomAvailabilityService->decorateCollection($property->rooms)
        );
        $roomAvailabilityService->decorateProperty($property);

        // Load approved property reviews with user relationship
        $reviews = $property->reviews()
            ->where('review_type', 'property')
            ->where('is_approved', 1)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate average rating and review count
        $avgRating = $reviews->avg('rating') ?? 0;
        $reviewCount = $reviews->count();

        // Fetch similar properties
        $similar = Property::where('status', 'approved')
            ->withApprovedReviewStats()
            ->where('id', '!=', $property->id)
            ->with([
                'images' => function ($query) {
                    $query->ordered();
                },
                'rooms' => function ($query) {
                    $query->select([
                        'id',
                        'property_id',
                        'room_name',
                        'capacity',
                        'current_occupancy',
                        'price',
                        'availability',
                    ])->withCount([
                        'bookings as active_confirmed_bookings_count' => function ($bookingQuery) {
                            $bookingQuery->where('status', 'confirmed');
                        }
                    ])->orderBy('price');
                }
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
                },
                'bookings as active_full_property_bookings_count' => function ($query) {
                    $query->whereNull('room_id')
                        ->where('status', 'confirmed');
                }
            ])
            ->where(function ($q) use ($property) {
                $q->where('city', $property->city)
                    ->orWhere('property_type', $property->property_type);
            })
            ->latest()
            ->take(4)
            ->get();

        $similar = $roomAvailabilityService->decoratePropertyCollection($similar);

        return view('listings.show', compact('property', 'reviews', 'avgRating', 'reviewCount', 'similar'));
    }
}
