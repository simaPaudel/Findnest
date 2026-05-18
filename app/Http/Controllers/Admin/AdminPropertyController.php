<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUpdatePropertyRequest;
use App\Models\Amenity;
use App\Models\Property;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminPropertyController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = in_array($request->query('tab'), ['current', 'requests'], true)
            ? $request->query('tab')
            : 'current';

        $searchTerm = trim((string) $request->query('q', ''));
        $selectedPropertyType = $request->query('property_type');
        $selectedVerification = $request->query('verification');
        $selectedCity = trim((string) $request->query('city', ''));
        $minRent = $this->normalizeMoneyFilter($request->query('min_rent'));
        $maxRent = $this->normalizeMoneyFilter($request->query('max_rent'));

        if ($minRent !== null && $maxRent !== null && $minRent > $maxRent) {
            [$minRent, $maxRent] = [$maxRent, $minRent];
        }

        $currentListingsCount = Property::where('status', 'approved')->count();
        $listingRequestsCount = Property::where('status', 'pending')->count();
        $propertyTypeOptions = [
            'house' => 'House',
            'room' => 'Room',
            'apartment' => 'Apartment',
        ];
        $verificationOptions = [
            'verified' => 'Verified',
            'unverified' => 'Unverified',
        ];

        $properties = Property::with(['owner', 'images', 'rooms.images'])
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            }, function ($query) use ($activeTab) {
                if ($activeTab === 'requests') {
                    $query->where('status', 'pending');
                } else {
                    $query->where('status', 'approved');
                }
            })
            ->when($searchTerm !== '', function ($query) use ($searchTerm) {
                $like = '%' . $searchTerm . '%';

                $query->where(function ($searchQuery) use ($like) {
                    $searchQuery->where('title', 'like', $like)
                        ->orWhere('city', 'like', $like)
                        ->orWhereHas('owner', function ($ownerQuery) use ($like) {
                            $ownerQuery->where('name', 'like', $like);
                        });
                });
            })
            ->when(in_array($selectedPropertyType, ['house', 'room', 'apartment'], true), function ($query) use ($selectedPropertyType) {
                $query->where('property_type', $selectedPropertyType);
            })
            ->when($selectedVerification === 'verified', function ($query) {
                $query->where('is_verified', true);
            })
            ->when($selectedVerification === 'unverified', function ($query) {
                $query->where('is_verified', false);
            })
            ->when($selectedCity !== '', function ($query) use ($selectedCity) {
                $query->where('city', 'like', '%' . $selectedCity . '%');
            })
            ->when($request->filled('user'), function ($query) use ($request) {
                $userId = (int) $request->integer('user');

                $query->where(function ($relatedQuery) use ($userId) {
                    $relatedQuery->where('owner_id', $userId)
                        ->orWhereHas('bookings', function ($bookingQuery) use ($userId) {
                            $bookingQuery->where('user_id', $userId);
                        });
                });
            })
            ->when($minRent !== null || $maxRent !== null, function ($query) use ($minRent, $maxRent) {
                $query->where(function ($rentQuery) use ($minRent, $maxRent) {
                    $rentQuery->where(function ($fullPropertyQuery) use ($minRent, $maxRent) {
                        $fullPropertyQuery->where('rental_mode', 'full_property');

                        if ($minRent !== null) {
                            $fullPropertyQuery->where('rent_price', '>=', $minRent);
                        }

                        if ($maxRent !== null) {
                            $fullPropertyQuery->where('rent_price', '<=', $maxRent);
                        }
                    })->orWhere(function ($roomRentalQuery) use ($minRent, $maxRent) {
                        $roomRentalQuery->where('rental_mode', 'per_room')
                            ->whereHas('rooms', function ($roomQuery) use ($minRent, $maxRent) {
                                if ($minRent !== null) {
                                    $roomQuery->where('price', '>=', $minRent);
                                }

                                if ($maxRent !== null) {
                                    $roomQuery->where('price', '<=', $maxRent);
                                }
                            });
                    });
                });
            })
            ->latest()
            ->paginate(6)
            ->withQueryString();

        return view('admin.properties.index', compact(
            'properties',
            'activeTab',
            'currentListingsCount',
            'listingRequestsCount',
            'searchTerm',
            'selectedPropertyType',
            'selectedVerification',
            'selectedCity',
            'minRent',
            'maxRent',
            'propertyTypeOptions',
            'verificationOptions'
        ));
    }

    public function show(Property $property)
    {
        $property->load([
            'owner',
            'images' => function ($query) {
                $query->ordered();
            },
            'amenities',
            'rooms' => function ($query) {
                $query->with(['images' => function ($imageQuery) {
                    $imageQuery->ordered();
                }])->orderBy('price');
            },
        ]);

        $reviewQuery = $property->reviews()
            ->where('review_type', 'property')
            ->where('is_approved', true);

        $reviewCount = (clone $reviewQuery)->count();
        $avgRating = $reviewCount > 0 ? round((float) $reviewQuery->avg('rating'), 1) : 0;
        $reviews = (clone $reviewQuery)
            ->with('user')
            ->latest()
            ->limit(6)
            ->get();
        $roomCount = $property->rooms->count();
        $availableRoomCount = $property->rooms->where('availability', true)->count();

        return view('admin.properties.show', compact(
            'property',
            'reviews',
            'reviewCount',
            'avgRating',
            'roomCount',
            'availableRoomCount'
        ));
    }

    public function edit(Property $property)
    {
        $property->load(['owner', 'amenities', 'rooms']);

        $amenities = Amenity::all();
        $propertyTypes = [
            'house' => 'House',
            'flat' => 'Flat/Apartment',
            'apartment' => 'Apartment',
            'room' => 'Room',
        ];
        $rentalModes = [
            'full_property' => 'Full Property Only',
            'per_room' => 'Per Room',
        ];
        $genderOptions = [
            'any' => 'Any',
            'male' => 'Male Only',
            'female' => 'Female Only',
        ];
        $roomCount = $property->rooms->count();
        $availableRoomCount = $property->rooms->where('availability', true)->count();

        return view('admin.properties.edit', compact(
            'property',
            'amenities',
            'propertyTypes',
            'rentalModes',
            'genderOptions',
            'roomCount',
            'availableRoomCount'
        ));
    }

    public function update(AdminUpdatePropertyRequest $request, Property $property)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $rentPrice = $validated['rental_mode'] === 'per_room'
                ? 0
                : (float) $validated['rent_price'];

            $property->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'address' => $validated['address'],
                'city' => $validated['city'],
                'location' => $validated['location'] ?? null,
                'landmark' => $validated['landmark'] ?? null,
                'rent_price' => $rentPrice,
                'property_type' => $validated['property_type'],
                'rental_mode' => $validated['rental_mode'],
                'gender_preference' => $validated['gender_preference'] ?? 'any',
                'furnished' => $request->boolean('furnished'),
                'total_rooms' => $validated['total_rooms'] ?? $property->total_rooms,
                'rules' => $validated['rules'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
            ]);

            if (array_key_exists('amenity_ids', $validated)) {
                $property->amenities()->sync($validated['amenity_ids'] ?? []);
            } else {
                $property->amenities()->detach();
            }

            DB::commit();

            return redirect()
                ->route('admin.properties.show', $property)
                ->with('success', 'Property updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Failed to update property. Please try again.');
        }
    }

    public function approve(Property $property)
    {
        $originalStatus = $property->status;

        $property->update(['status' => 'approved']);

        if ($originalStatus !== 'approved' && $property->owner_id) {
            try {
                NotificationService::sendNotification(
                    (int) $property->owner_id,
                    'property',
                    'Listing approved',
                    'Your property listing has been approved.',
                    route('owner.listings.index')
                );
            } catch (\Throwable $notificationError) {
                // Notification failures must not block admin actions.
            }
        }

        return back()->with('success', 'Property approved successfully.');
    }

    public function reject(Property $property)
    {
        $originalStatus = $property->status;

        $property->update(['status' => 'rejected']);

        if ($originalStatus !== 'rejected' && $property->owner_id) {
            try {
                NotificationService::sendNotification(
                    (int) $property->owner_id,
                    'property',
                    'Listing rejected',
                    'Your property listing has been rejected.',
                    route('owner.listings.index')
                );
            } catch (\Throwable $notificationError) {
                // Notification failures must not block admin actions.
            }
        }

        return back()->with('success', 'Property rejected successfully.');
    }

    public function verify(Property $property)
    {
        $property->update([
            'is_verified' => ! $property->is_verified,
        ]);

        return back()->with('success', 'Property verification updated successfully.');
    }

    public function destroy(Property $property)
    {
        try {
            DB::beginTransaction();

            foreach ($property->images as $image) {
                Storage::disk('public')->delete($image->path);
            }

            foreach ($property->rooms as $room) {
                foreach ($room->images as $image) {
                    Storage::disk('public')->delete($image->path);
                }
            }

            $property->rooms()->delete();
            $property->delete();

            DB::commit();

            return back()->with('success', 'Property removed successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Failed to remove property. Please try again.');
        }
    }

    private function normalizeMoneyFilter(mixed $value): ?float
    {
        if (!is_numeric($value)) {
            return null;
        }

        $amount = (float) $value;

        return $amount >= 0 ? $amount : null;
    }
}
