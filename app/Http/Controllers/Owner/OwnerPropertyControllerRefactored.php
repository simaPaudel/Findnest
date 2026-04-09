<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePropertyWithRentalModeRequest;
use App\Http\Requests\UpdatePropertyWithRentalModeRequest;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Amenity;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class OwnerPropertyController extends Controller
{
    /**
     * Display a listing of the owner's properties.
     */
    public function index()
    {
        $properties = Property::where('owner_id', Auth::id())
            ->with(['images', 'amenities', 'rooms'])
            ->latest()
            ->paginate(10);

        return view('owner.listings.index', compact('properties'));
    }

    /**
     * Show the form for creating a new property.
     */
    public function create()
    {
        $amenities = Amenity::all();
        $rentalModes = [
            'full_property' => 'Full Property Only',
            'rooms' => 'Rooms Only',
            'hybrid' => 'Both Full Property & Rooms',
        ];

        return view('owner.listings.create', compact('amenities', 'rentalModes'));
    }

    /**
     * Store a newly created property in storage.
     * 
     * This method handles both full-property and room-based rentals based on rental_mode.
     */
    public function store(StorePropertyWithRentalModeRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
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
                'rental_mode' => $validated['rental_mode'],
                'gender_preference' => $validated['gender_preference'] ?? 'any',
                'furnished' => $request->boolean('furnished'),
                'total_rooms' => $validated['total_rooms'] ?? 1,
                'rules' => $validated['rules'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'status' => 'pending',
                'is_verified' => false,
            ]);

            // Attach amenities to the property
            if (!empty($validated['amenity_ids'])) {
                $property->amenities()->attach($validated['amenity_ids']);
            }

            // Handle property images
            if ($request->hasFile('images')) {
                $this->storePropertyImages($property, $request->file('images'), $validated['title']);
            }

            // Handle room creation if rental_mode is 'per_room'
            if ($validated['rental_mode'] === 'per_room') {
                if (!empty($validated['rooms'])) {
                    foreach ($validated['rooms'] as $roomData) {
                        $this->createRoomRecord($property, $roomData);
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('owner.listings.index')
                ->with('success', 'Property created successfully! Awaiting admin approval.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create property: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified property.
     */
    public function edit(Property $property)
    {
        if ($property->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $property->load(['images', 'amenities', 'rooms.images']);

        $amenities = Amenity::all();
        $rentalModes = [
            'full_property' => 'Full Property Only',
            'rooms' => 'Rooms Only',
            'hybrid' => 'Both Full Property & Rooms',
        ];

        return view('owner.listings.edit', compact('property', 'amenities', 'rentalModes'));
    }

    /**
     * Update the specified property in storage.
     */
    public function update(UpdatePropertyWithRentalModeRequest $request, Property $property)
    {
        if ($property->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validated();

        DB::beginTransaction();

        try {
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
                'rental_mode' => $validated['rental_mode'],
                'gender_preference' => $validated['gender_preference'] ?? 'any',
                'furnished' => $request->boolean('furnished'),
                'total_rooms' => $validated['total_rooms'] ?? 1,
                'rules' => $validated['rules'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
            ]);

            // Sync amenities
            if (isset($validated['amenity_ids'])) {
                $property->amenities()->sync($validated['amenity_ids']);
            } else {
                $property->amenities()->detach();
            }

            // Handle new property images
            if ($request->hasFile('images')) {
                $this->storePropertyImages($property, $request->file('images'), $validated['title']);
            }

            // Handle room updates if rental_mode supports rooms
            if ($validated['rental_mode'] === 'per_room') {
                if (!empty($validated['rooms'])) {
                    $this->updatePropertyRooms($property, $validated['rooms']);
                }
            }

            DB::commit();

            return redirect()
                ->route('owner.listings.index')
                ->with('success', 'Property updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update property: ' . $e->getMessage());
        }
    }

    /**
     * Delete the specified property.
     */
    public function destroy(Property $property)
    {
        if ($property->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Delete all property images from storage
            foreach ($property->images as $image) {
                Storage::disk('public')->delete($image->path);
            }

            // Delete all room images from storage
            foreach ($property->rooms as $room) {
                foreach ($room->images as $image) {
                    Storage::disk('public')->delete($image->path);
                }
            }

            // Delete the property (cascades to rooms, images, bookings, etc.)
            $property->delete();

            return redirect()
                ->route('owner.listings.index')
                ->with('success', 'Property deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to delete property: ' . $e->getMessage());
        }
    }

    /**
     * Delete a property image.
     */
    public function deleteImage(Property $property, PropertyImage $image)
    {
        if ($property->owner_id !== Auth::id() || $image->property_id !== $property->id) {
            abort(403, 'Unauthorized action.');
        }

        try {
            Storage::disk('public')->delete($image->path);
            $image->delete();

            return redirect()->back()->with('success', 'Image deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete image.');
        }
    }

    /**
     * Set a property image as primary.
     */
    public function setPrimaryImage(Property $property, PropertyImage $image)
    {
        if ($property->owner_id !== Auth::id() || $image->property_id !== $property->id) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $image->setPrimary();
            return redirect()->back()->with('success', 'Primary image updated.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update primary image.');
        }
    }

    /**
     * Toggle property status.
     */
    public function toggleStatus(Property $property)
    {
        if ($property->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $newStatus = $property->status === 'approved' ? 'pending' : 'approved';
            $property->update(['status' => $newStatus]);

            return redirect()->back()->with('success', 'Property status updated.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update status.');
        }
    }

    // ==================== ROOM MANAGEMENT METHODS ====================

    /**
     * Show the form for creating a new room.
     */
    public function createRoom(Property $property)
    {
        if ($property->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if (!$property->canRentRooms()) {
            return redirect()->back()->with('error', 'This property does not support room-based rental.');
        }

        return view('owner.rooms.create', compact('property'));
    }

    /**
     * Store a newly created room.
     */
    public function storeRoom(StoreRoomRequest $request, Property $property)
    {
        if ($property->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $room = $this->createRoomRecord($property, $validated);

            // Handle room images
            if ($request->hasFile('images')) {
                $this->storeRoomImages($room, $request->file('images'));
            }

            DB::commit();

            return redirect()
                ->route('owner.rooms.index', $property)
                ->with('success', 'Room created successfully.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create room: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing a room.
     */
    public function editRoom(Property $property, Room $room)
    {
        if ($property->owner_id !== Auth::id() || $room->property_id !== $property->id) {
            abort(403, 'Unauthorized action.');
        }

        $room->load('images');

        return view('owner.rooms.edit', compact('property', 'room'));
    }

    /**
     * Update the specified room.
     */
    public function updateRoom(UpdateRoomRequest $request, Property $property, Room $room)
    {
        if ($property->owner_id !== Auth::id() || $room->property_id !== $property->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $room->update([
                'room_name' => $validated['room_name'],
                'room_number' => $validated['room_number'] ?? null,
                'capacity' => $validated['capacity'],
                'current_occupancy' => 0,
                'price' => $validated['price'],
                'availability' => $request->boolean('availability', true),
                'room_features' => $validated['room_features'] ?? null,
            ]);

            // Handle new room images
            if ($request->hasFile('images')) {
                $this->storeRoomImages($room, $request->file('images'));
            }

            DB::commit();

            return redirect()
                ->route('owner.rooms.index', $property)
                ->with('success', 'Room updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update room: ' . $e->getMessage());
        }
    }

    /**
     * Delete the specified room.
     */
    public function destroyRoom(Property $property, Room $room)
    {
        if ($property->owner_id !== Auth::id() || $room->property_id !== $property->id) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Delete all room images from storage
            foreach ($room->images as $image) {
                Storage::disk('public')->delete($image->path);
            }

            // Delete the room (cascades to images and bookings)
            $room->delete();

            return redirect()
                ->back()
                ->with('success', 'Room deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to delete room: ' . $e->getMessage());
        }
    }

    /**
     * Delete a room image.
     */
    public function deleteRoomImage(Property $property, Room $room, RoomImage $image)
    {
        if (
            $property->owner_id !== Auth::id() ||
            $room->property_id !== $property->id ||
            $image->room_id !== $room->id
        ) {
            abort(403, 'Unauthorized action.');
        }

        try {
            Storage::disk('public')->delete($image->path);
            $image->delete();

            return redirect()->back()->with('success', 'Image deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete image.');
        }
    }

    /**
     * Set a room image as primary.
     */
    public function setRoomPrimaryImage(Property $property, Room $room, RoomImage $image)
    {
        if (
            $property->owner_id !== Auth::id() ||
            $room->property_id !== $property->id ||
            $image->room_id !== $room->id
        ) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $image->setPrimary();
            return redirect()->back()->with('success', 'Primary image updated.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update primary image.');
        }
    }

    /**
     * Display a listing of rooms for a property.
     */
    public function roomsIndex(Property $property)
    {
        if ($property->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if (!$property->canRentRooms()) {
            return redirect()->back()->with('error', 'This property does not support room-based rental.');
        }

        $rooms = $property->rooms()->paginate(20);

        return view('owner.rooms.index', compact('property', 'rooms'));
    }

    // ==================== HELPER METHODS ====================

    /**
     * Create a room from data array.
     */
    private function createRoomRecord(Property $property, array $roomData)
    {
        return Room::create([
            'property_id' => $property->id,
            'room_name' => $roomData['room_name'],
            'room_number' => $roomData['room_number'] ?? null,
            'capacity' => $roomData['capacity'],
            'current_occupancy' => 0,
            'price' => $roomData['price'],
            'availability' => true,
            'room_features' => $roomData['room_features'] ?? null,
        ]);
    }

    /**
     * Update multiple rooms for a property.
     */
    private function updatePropertyRooms(Property $property, array $roomsData)
    {
        foreach ($roomsData as $roomData) {
            if (isset($roomData['id'])) {
                // Update existing room
                $room = Room::find($roomData['id']);
                if ($room && $room->property_id === $property->id) {
                    $room->update([
                        'room_name' => $roomData['room_name'],
                        'room_number' => $roomData['room_number'] ?? $room->room_number,
                        'capacity' => $roomData['capacity'],
                        'price' => $roomData['price'],
                        'room_features' => $roomData['room_features'] ?? $room->room_features,
                    ]);
                }
            } else {
                // Create new room
                $this->createRoomRecord($property, $roomData);
            }
        }
    }

    /**
     * Store property images and create PropertyImage records.
     */
    private function storePropertyImages(Property $property, $images, $propertyTitle)
    {
        $imageOrder = $property->images()->max('order') ?? 0;
        $isPrimary = $property->images()->count() === 0;

        foreach ($images as $image) {
            $path = $image->store('properties', 'public');

            PropertyImage::create([
                'property_id' => $property->id,
                'path' => $path,
                'alt_text' => $propertyTitle . ' Image ' . ($imageOrder + 1),
                'order' => $imageOrder,
                'is_primary' => $isPrimary,
            ]);

            $isPrimary = false;
            $imageOrder++;
        }
    }

    /**
     * Store room images and create RoomImage records.
     */
    private function storeRoomImages(Room $room, $images)
    {
        $imageOrder = $room->images()->max('order') ?? 0;
        $isPrimary = $room->images()->count() === 0;

        foreach ($images as $image) {
            $path = $image->store('rooms', 'public');

            RoomImage::create([
                'room_id' => $room->id,
                'path' => $path,
                'alt_text' => $room->room_name . ' Image ' . ($imageOrder + 1),
                'order' => $imageOrder,
                'is_primary' => $isPrimary,
            ]);

            $isPrimary = false;
            $imageOrder++;
        }
    }
}
