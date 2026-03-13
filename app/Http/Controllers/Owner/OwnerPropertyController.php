<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OwnerPropertyController extends Controller
{
    /**
     * Display a listing of the owner's properties.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $properties = Property::where('owner_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('owner.listings.index', compact('properties'));
    }

    /**
     * Show the form for creating a new property.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('owner.listings.create');
    }

    /**
     * Store a newly created property in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'location' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'rent_price' => 'required|numeric|min:0',
            'room_type' => 'required|in:single,shared,flat',
            'gender_preference' => 'nullable|in:any,male,female',
            'furnished' => 'boolean',
            'total_rooms' => 'nullable|integer|min:1',
            'amenities' => 'nullable|string',
            'rules' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Handle photo uploads
        $photosPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('properties', 'public');
                $photosPaths[] = $path;
            }
        }

        // Process amenities: convert comma-separated string to array
        $amenitiesArray = null;
        if (!empty($validated['amenities'])) {
            $amenitiesArray = array_map('trim', explode(',', $validated['amenities']));
            $amenitiesArray = array_filter($amenitiesArray); // Remove empty values
        }

        // Create property
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
            'gender_preference' => $validated['gender_preference'] ?? 'any',
            'furnished' => $request->has('furnished') ? true : false,
            'total_rooms' => $validated['total_rooms'] ?? 1,
            'amenities' => $amenitiesArray,
            'photos' => !empty($photosPaths) ? $photosPaths : null,
            'rules' => $validated['rules'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'status' => 'pending',
            'is_verified' => false,
        ]);

        return redirect()
            ->route('owner.listings.index')
            ->with('success', 'Property created successfully!');
    }

    /**
     * Show the form for editing the specified property.
     *
     * @param  \App\Models\Property  $property
     * @return \Illuminate\View\View
     */
    public function edit(Property $property)
    {
        // Authorization: ensure property belongs to current owner
        if ($property->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('owner.listings.edit', compact('property'));
    }

    /**
     * Update the specified property in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Property $property)
    {
        // Authorization: ensure property belongs to current owner
        if ($property->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'location' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'rent_price' => 'required|numeric|min:0',
            'room_type' => 'required|in:single,shared,flat',
            'gender_preference' => 'nullable|in:any,male,female',
            'furnished' => 'boolean',
            'total_rooms' => 'nullable|integer|min:1',
            'amenities' => 'nullable|string',
            'rules' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Handle photo uploads (append to existing)
        $existingPhotos = is_array($property->photos) ? $property->photos : [];
        $photosPaths = $existingPhotos;

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('properties', 'public');
                $photosPaths[] = $path;
            }
        }

        // Process amenities: convert comma-separated string to array
        $amenitiesArray = null;
        if (!empty($validated['amenities'])) {
            $amenitiesArray = array_map('trim', explode(',', $validated['amenities']));
            $amenitiesArray = array_filter($amenitiesArray); // Remove empty values
        }

        // Update property
        $property->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'address' => $validated['address'],
            'city' => $validated['city'],
            'location' => $validated['location'] ?? null,
            'landmark' => $validated['landmark'] ?? null,
            'rent_price' => $validated['rent_price'],
            'room_type' => $validated['room_type'],
            'gender_preference' => $validated['gender_preference'] ?? 'any',
            'furnished' => $request->has('furnished') ? true : false,
            'total_rooms' => $validated['total_rooms'] ?? 1,
            'amenities' => $amenitiesArray,
            'photos' => !empty($photosPaths) ? $photosPaths : null,
            'rules' => $validated['rules'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
        ]);

        return redirect()
            ->route('owner.listings.index')
            ->with('success', 'Property updated successfully!');
    }

    /**
     * Remove the specified property from storage.
     *
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Property $property)
    {
        // Authorization: ensure property belongs to current owner
        if ($property->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete associated photos from storage
        if ($property->photos) {
            $photos = json_decode($property->photos, true);
            foreach ($photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        $property->delete();

        return redirect()
            ->route('owner.listings.index')
            ->with('success', 'Property deleted successfully!');
    }

    /**
     * Toggle the property status between approved and pending.
     *
     * @param  \App\Models\Property  $property
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus(Property $property)
    {
        // Authorization: ensure property belongs to current owner
        if ($property->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $newStatus = $property->status === 'approved' ? 'pending' : 'approved';
        $property->update(['status' => $newStatus]);

        return redirect()
            ->route('owner.listings.index')
            ->with('success', 'Property status updated to ' . $newStatus . '!');
    }
}
