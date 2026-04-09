<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SavedListing;
use App\Models\Property;
use App\Services\RoomAvailabilityService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SavedListingController extends Controller
{
    /**
     * Display user's saved listings.
     *
     * @return \Illuminate\View\View
     */
    public function index(RoomAvailabilityService $roomAvailabilityService)
    {
        $savedListings = SavedListing::where('user_id', Auth::id())
            ->with([
                'property' => function ($query) {
                    $query->with([
                        'images' => function ($imageQuery) {
                            $imageQuery->ordered();
                        },
                        'rooms' => function ($roomQuery) {
                            $roomQuery->with(['images' => function ($imageQuery) {
                                $imageQuery->ordered();
                            }])->withCount([
                                'bookings as active_confirmed_bookings_count' => function ($bookingQuery) {
                                    $bookingQuery->where('status', 'confirmed');
                                }
                            ]);
                        },
                    ]);
                }
            ])
            ->latest()
            ->paginate(12);

        $savedListings->getCollection()->transform(function ($saved) use ($roomAvailabilityService) {
            if ($saved->property) {
                $roomAvailabilityService->decorateProperty($saved->property);
            }

            return $saved;
        });

        return view('user.saved.index', compact('savedListings'));
    }

    /**
     * Save a listing for the authenticated user
     *
     * @param Property $property
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Property $property)
    {
        try {
            $userId = Auth::id();
            
            // Check if already saved
            $existing = SavedListing::where('user_id', $userId)
                ->where('property_id', $property->id)
                ->first();
            
            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Listing already saved',
                    'is_saved' => true,
                ], 409);
            }
            
            // Save the listing
            SavedListing::create([
                'user_id' => $userId,
                'property_id' => $property->id,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Listing saved successfully',
                'is_saved' => true,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving listing: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Unsave a listing for the authenticated user
     *
     * @param Property $property
     * @return \Illuminate\Http\JsonResponse
     */
    public function unsave(Property $property)
    {
        try {
            $userId = Auth::id();
            
            // Delete the saved listing
            $deleted = SavedListing::where('user_id', $userId)
                ->where('property_id', $property->id)
                ->delete();
            
            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Listing not found in saved',
                    'is_saved' => false,
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Listing removed from saved',
                'is_saved' => false,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing listing: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check if a property is saved by the authenticated user
     *
     * @param Property $property
     * @return \Illuminate\Http\JsonResponse
     */
    public function isSaved(Property $property)
    {
        try {
            $isSaved = SavedListing::where('user_id', Auth::id())
                ->where('property_id', $property->id)
                ->exists();
            
            return response()->json([
                'success' => true,
                'is_saved' => $isSaved,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking saved status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a saved listing
     *
     * @param SavedListing $savedListing
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(SavedListing $savedListing)
    {
        // Ensure user is deleting their own saved listing
        if ($savedListing->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $savedListing->delete();

        return redirect()->route('user.saved-listings.index')
            ->with('success', 'Listing removed from saved.');
    }
}
