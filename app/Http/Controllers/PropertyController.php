<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Room;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PropertyController extends Controller
{
    /**
     * Display a listing of all approved properties.
     */
    public function index()
    {
        $properties = Property::with(['owner', 'rooms', 'images'])
            ->where('status', 'approved')
            ->latest()
            ->paginate(10);

        return view('properties.index', compact('properties'));
    }

    /**
     * Show the form for creating a new property.
     */
    public function create()
    {
        // Get list of property types and rental modes for the form
        $propertyTypes = ['house', 'flat', 'room', 'apartment', 'hostel', 'other'];
        $rentalModes = ['full_property', 'rooms', 'hybrid'];

        return view('properties.create', compact('propertyTypes', 'rentalModes'));
    }

    /**
     * Store a newly created property with optional rooms.
     * 
     * Uses StorePropertyRequest for comprehensive validation including:
     * - property_type validation
     * - rental_mode validation
     * - Conditional room requirements (rooms required if rental_mode is 'rooms' or 'hybrid')
     * - Property type / rental mode combination validation
     */
    public function store(StorePropertyRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            // Create the property
            $property = Property::create([
                'owner_id' => Auth::id(),
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'address' => $validated['address'],
                'city' => $validated['city'],
                'location' => $validated['location'] ?? null,
                'landmark' => $validated['landmark'] ?? null,
                'rent_price' => $validated['rent_price'],
                'room_type' => $validated['room_type'],
                'property_type' => $validated['property_type'],
                'rental_mode' => $validated['rental_mode'],
                'gender_preference' => $validated['gender_preference'] ?? 'any',
                'furnished' => $validated['furnished'] ?? false,
                'total_rooms' => $validated['total_rooms'] ?? 1,
                'rules' => $validated['rules'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'status' => 'pending',
            ]);

            // Create rooms if rental_mode is 'rooms' or 'hybrid'
            if (!empty($validated['rooms']) && $property->canCreateRooms()) {
                foreach ($validated['rooms'] as $roomData) {
                    Room::create([
                        'property_id' => $property->id,
                        'room_name' => $roomData['room_name'],
                        'room_number' => $roomData['room_number'] ?? null,
                        'capacity' => $roomData['capacity'],
                        'price' => $roomData['price'],
                        'room_features' => $roomData['room_features'] ?? null,
                        'availability' => true,
                        'current_occupancy' => 0,
                    ]);
                }
            }

            // Handle amenities (if many-to-many relationship exists)
            if (!empty($validated['amenity_ids'])) {
                $property->amenities()->attach($validated['amenity_ids']);
            }

            DB::commit();

            return redirect()->route('properties.show', $property)
                ->with('success', "Property '{$property->title}' created successfully! " .
                    "It's pending verification by our team.");
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create property. Please try again. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified property.
     */
    public function show($id)
    {
        $property = Property::with([
            'owner',
            'rooms',
            'reviews',
            'amenities',
            'images',
        ])->findOrFail($id);

        return view('properties.show', compact('property'));
    }

    /**
     * Show the form for editing a property.
     */
    public function edit($id)
    {
        $property = Property::where('owner_id', Auth::id())->findOrFail($id);

        $propertyTypes = ['house', 'flat', 'room', 'apartment', 'hostel', 'other'];
        $rentalModes = ['full_property', 'rooms', 'hybrid'];

        return view('properties.edit', compact('property', 'propertyTypes', 'rentalModes'));
    }

    /**
     * Update the specified property.
     * 
     * Uses UpdatePropertyRequest for comprehensive validation. 
     * Handles rental_mode changes with warnings if needed.
     */
    public function update(UpdatePropertyRequest $request, $id)
    {
        $property = Property::where('owner_id', Auth::id())->findOrFail($id);
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            // Update property details
            $property->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'address' => $validated['address'],
                'city' => $validated['city'],
                'location' => $validated['location'] ?? null,
                'landmark' => $validated['landmark'] ?? null,
                'rent_price' => $validated['rent_price'],
                'room_type' => $validated['room_type'],
                'property_type' => $validated['property_type'],
                'rental_mode' => $validated['rental_mode'],
                'gender_preference' => $validated['gender_preference'] ?? 'any',
                'furnished' => $validated['furnished'] ?? false,
                'total_rooms' => $validated['total_rooms'] ?? 1,
                'rules' => $validated['rules'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
            ]);

            // Handle rooms if rental_mode allows room creation
            if ($property->canCreateRooms() && !empty($validated['rooms'])) {
                foreach ($validated['rooms'] as $roomData) {
                    if (isset($roomData['id'])) {
                        // Update existing room
                        Room::find($roomData['id'])->update([
                            'room_name' => $roomData['room_name'],
                            'room_number' => $roomData['room_number'] ?? null,
                            'capacity' => $roomData['capacity'],
                            'price' => $roomData['price'],
                            'room_features' => $roomData['room_features'] ?? null,
                        ]);
                    } else {
                        // Create new room
                        Room::create([
                            'property_id' => $property->id,
                            'room_name' => $roomData['room_name'],
                            'room_number' => $roomData['room_number'] ?? null,
                            'capacity' => $roomData['capacity'],
                            'price' => $roomData['price'],
                            'room_features' => $roomData['room_features'] ?? null,
                            'availability' => true,
                            'current_occupancy' => 0,
                        ]);
                    }
                }
            }

            // Handle amenities
            if (isset($validated['amenity_ids'])) {
                $property->amenities()->sync($validated['amenity_ids']);
            }

            DB::commit();

            return redirect()->route('properties.show', $property)
                ->with('success', 'Property updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update property. Please try again. ' . $e->getMessage());
        }
    }

    /**
     * Get property details as JSON (useful for AJAX updates).
     */
    public function getPropertyDetails($id)
    {
        $property = Property::find($id);

        if (!$property) {
            return response()->json(['error' => 'Property not found'], 404);
        }

        return response()->json([
            'id' => $property->id,
            'title' => $property->title,
            'property_type' => $property->property_type,
            'rental_mode' => $property->rental_mode,
            'can_create_rooms' => $property->canCreateRooms(),
            'requires_rooms' => $property->requiresRooms(),
            'room_count' => $property->rooms()->count(),
            'recommended_mode' => $property->getRecommendedRentalMode(),
        ]);
    }

    /**
     * Delete the specified property.
     */
    public function destroy($id)
    {
        $property = Property::where('owner_id', Auth::id())->findOrFail($id);

        try {
            DB::beginTransaction();

            // Delete related rooms (cascade is handled by DB constraints too)
            $property->rooms()->delete();

            // Delete property
            $property->delete();

            DB::commit();

            return redirect()->route('properties.index')
                ->with('success', 'Property deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to delete property. Please try again.');
        }
    }
}
