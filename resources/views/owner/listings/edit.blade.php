@extends('owner.layout')

@section('title', 'Edit Property')
@section('page-title', 'Edit Property')

@php
    $roomForms = old('rooms');
    if ($roomForms === null) {
        $roomForms = $property->rooms->values()->map(fn ($room) => [
            'id' => $room->id,
            'room_name' => $room->room_name,
            'room_number' => $room->room_number,
            'capacity' => $room->capacity,
            'price' => $room->price,
            'availability' => $room->availability,
            'room_features' => $room->room_features,
        ])->toArray();
    }
    $roomForms = array_values($roomForms ?? []);
@endphp

@section('content')
<div class="content-card form-surface">
    @if ($errors->any())
    <div class="form-error-panel">
        <h3>Validation Errors</h3>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('owner.listings.update', $property) }}" enctype="multipart/form-data" id="property-edit-form">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="form-section">
                <h3 class="form-section-title">Basic Information</h3>
                <p class="form-section-muted">Update the main listing details shown to renters.</p>

                <div class="form-group">
                    <label for="title" class="form-label">Property Title *</label>
                    <input type="text" name="title" id="title" class="form-input @error('title') error @enderror" value="{{ old('title', $property->title) }}" required>
                    @error('title')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" rows="4" class="form-input @error('description') error @enderror">{{ old('description', $property->description) }}</textarea>
                    @error('description')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="property_type" class="form-label">Property Type *</label>
                        <select name="property_type" id="property_type" class="form-input @error('property_type') error @enderror" required>
                            <option value="">Select Type</option>
                            <option value="house" {{ old('property_type', $property->property_type) === 'house' ? 'selected' : '' }}>House</option>
                            <option value="flat" {{ old('property_type', $property->property_type) === 'flat' ? 'selected' : '' }}>Flat/Apartment</option>
                            <option value="apartment" {{ old('property_type', $property->property_type) === 'apartment' ? 'selected' : '' }}>Multi-room Apartment</option>
                            <option value="room" {{ old('property_type', $property->property_type) === 'room' ? 'selected' : '' }}>Single Room</option>
                        </select>
                        @error('property_type')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="rental_mode" class="form-label">Rental Mode *</label>
                        <select name="rental_mode" id="rental_mode" class="form-input @error('rental_mode') error @enderror" required onchange="toggleRentalSections()">
                            <option value="full_property" {{ old('rental_mode', $property->rental_mode) === 'full_property' ? 'selected' : '' }}>Full Property Only</option>
                            <option value="per_room" {{ old('rental_mode', $property->rental_mode) === 'per_room' ? 'selected' : '' }}>Per Room</option>
                        </select>
                        @error('rental_mode')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-group" id="rent-price-group">
                    <label for="rent_price" class="form-label">Rent Price/Month (NPR) *</label>
                    <input type="number" name="rent_price" id="rent_price" class="form-input @error('rent_price') error @enderror" value="{{ old('rent_price', $property->rent_price) }}" min="0" step="0.01">
                    @error('rent_price')<span class="form-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-section">
                <h3 class="form-section-title">Location Details</h3>
                <p class="form-section-muted">Review the address information and update it where needed.</p>

                <div class="form-group">
                    <label for="address" class="form-label">Address *</label>
                    <input type="text" name="address" id="address" class="form-input @error('address') error @enderror" value="{{ old('address', $property->address) }}" required>
                    @error('address')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="city" class="form-label">City *</label>
                        <input type="text" name="city" id="city" class="form-input @error('city') error @enderror" value="{{ old('city', $property->city) }}" required>
                        @error('city')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="location" class="form-label">Location/Area</label>
                        <input type="text" name="location" id="location" class="form-input @error('location') error @enderror" value="{{ old('location', $property->location) }}">
                        @error('location')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="landmark" class="form-label">Landmark</label>
                    <input type="text" name="landmark" id="landmark" class="form-input @error('landmark') error @enderror" value="{{ old('landmark', $property->landmark) }}">
                    @error('landmark')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="latitude" class="form-label">Latitude</label>
                        <input type="number" name="latitude" id="latitude" class="form-input @error('latitude') error @enderror" value="{{ old('latitude', $property->latitude) }}" step="any">
                        @error('latitude')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="longitude" class="form-label">Longitude</label>
                        <input type="number" name="longitude" id="longitude" class="form-input @error('longitude') error @enderror" value="{{ old('longitude', $property->longitude) }}" step="any">
                        @error('longitude')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                @include('components.leaflet-property-map', [
                    'mapId' => 'owner-edit-property-map',
                    'mode' => 'picker',
                    'latitudeInputId' => 'latitude',
                    'longitudeInputId' => 'longitude',
                    'initialLatitude' => old('latitude', $property->latitude),
                    'initialLongitude' => old('longitude', $property->longitude),
                    'defaultLatitude' => 27.7172,
                    'defaultLongitude' => 85.3240,
                    'defaultZoom' => 12,
                    'height' => '320px',
                    'title' => 'Pick Location on Map',
                    'helpText' => 'Click on the map to move the pin. The latitude and longitude fields will stay in sync.',
                ])
            </div>

            <div class="form-section">
                <h3 class="form-section-title">Property Details</h3>
                <p class="form-section-muted">Manage total rooms, amenities, furnishing, and house rules.</p>

                <div class="form-row">
                    <div class="form-group">
                        <label for="total_rooms" class="form-label">Total Rooms</label>
                        <input type="number" name="total_rooms" id="total_rooms" class="form-input @error('total_rooms') error @enderror" value="{{ old('total_rooms', $property->total_rooms) }}" min="1">
                        @error('total_rooms')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="gender_preference" class="form-label">Gender Preference</label>
                        <select name="gender_preference" id="gender_preference" class="form-input @error('gender_preference') error @enderror">
                            <option value="any" {{ old('gender_preference', $property->gender_preference) === 'any' ? 'selected' : '' }}>Any</option>
                            <option value="male" {{ old('gender_preference', $property->gender_preference) === 'male' ? 'selected' : '' }}>Male Only</option>
                            <option value="female" {{ old('gender_preference', $property->gender_preference) === 'female' ? 'selected' : '' }}>Female Only</option>
                        </select>
                        @error('gender_preference')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-checkbox">
                        <input type="checkbox" name="furnished" value="1" {{ old('furnished', $property->furnished) ? 'checked' : '' }}>
                        <span>Furnished</span>
                    </label>
                </div>
                <div class="form-group">
                    <label class="form-label">Amenities</label>
                    <div class="amenities-grid">
                        @foreach($amenities as $amenity)
                        <label class="amenity-option">
                            <input type="checkbox" name="amenity_ids[]" value="{{ $amenity->id }}" {{ in_array($amenity->id, old('amenity_ids', $property->amenities->pluck('id')->all())) ? 'checked' : '' }} class="w-4 h-4">
                            <span>{{ $amenity->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label for="rules" class="form-label">House Rules</label>
                    <textarea name="rules" id="rules" rows="3" class="form-input @error('rules') error @enderror">{{ old('rules', $property->rules) }}</textarea>
                    @error('rules')<span class="form-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-section" id="rooms-management-section">
                <div class="form-panel">
                    <div class="form-panel-header">
                        <div>
                            <h3 class="form-panel-title">Rooms Management</h3>
                            <p class="form-panel-copy">Update room details here. Existing room images can be deleted or marked as primary, and new room images can be added below.</p>
                        </div>
                        <button type="button" class="btn-primary" onclick="addRoomCard()">Add Room</button>
                    </div>
                    @error('rooms')<span class="form-error block mb-4">{{ $message }}</span>@enderror
                    <div id="rooms-empty-state" class="form-panel-empty" style="{{ count($roomForms) ? 'display: none;' : '' }}">No rooms added yet.</div>
                    <div id="rooms-container" class="room-stack">
                        @foreach ($roomForms as $index => $roomForm)
                        @php
                            $existingRoom = !empty($roomForm['id']) ? $property->rooms->firstWhere('id', (int) $roomForm['id']) : null;
                            $roomImages = $existingRoom ? $existingRoom->images->sortBy('order') : collect();
                            $availabilityValue = old("rooms.$index.availability", $roomForm['availability'] ?? false);
                        @endphp
                        <div class="room-card">
                            <div class="room-card-header">
                                <div>
                                    <h4 class="room-card-title" data-room-title>Room {{ $index + 1 }}</h4>
                                    <p class="room-card-status">{{ $existingRoom ? 'Existing room' : 'New room' }}</p>
                                </div>
                                @if($existingRoom)
                                <button type="button" class="room-card-action danger" onclick="deleteExistingRoom({{ $existingRoom->id }})">Delete Room</button>
                                @else
                                <button type="button" class="room-card-action" onclick="removeNewRoomCard(this)">Remove</button>
                                @endif
                            </div>
                            @if($existingRoom)
                            <input type="hidden" name="rooms[{{ $index }}][id]" value="{{ $existingRoom->id }}">
                            @endif
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Room Name *</label>
                                    <input type="text" name="rooms[{{ $index }}][room_name]" class="form-input @error("rooms.$index.room_name") error @enderror" value="{{ old("rooms.$index.room_name", $roomForm['room_name'] ?? '') }}" required>
                                    @error("rooms.$index.room_name")<span class="form-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Room Number</label>
                                    <input type="text" name="rooms[{{ $index }}][room_number]" class="form-input @error("rooms.$index.room_number") error @enderror" value="{{ old("rooms.$index.room_number", $roomForm['room_number'] ?? '') }}">
                                    @error("rooms.$index.room_number")<span class="form-error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Capacity *</label>
                                    <input type="number" name="rooms[{{ $index }}][capacity]" class="form-input @error("rooms.$index.capacity") error @enderror" value="{{ old("rooms.$index.capacity", $roomForm['capacity'] ?? 1) }}" min="1" max="100" required>
                                    @error("rooms.$index.capacity")<span class="form-error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Price per Month (NPR) *</label>
                                    <input type="number" name="rooms[{{ $index }}][price]" class="form-input @error("rooms.$index.price") error @enderror" value="{{ old("rooms.$index.price", $roomForm['price'] ?? '') }}" min="0" step="0.01" required>
                                    @error("rooms.$index.price")<span class="form-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-group flex items-center">
                                    <label class="form-checkbox">
                                        <input type="hidden" name="rooms[{{ $index }}][availability]" value="0">
                                        <input type="checkbox" name="rooms[{{ $index }}][availability]" value="1" {{ $availabilityValue ? 'checked' : '' }}>
                                        <span>Available for Booking</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Room Features</label>
                                <textarea name="rooms[{{ $index }}][room_features]" rows="3" class="form-input @error("rooms.$index.room_features") error @enderror">{{ old("rooms.$index.room_features", $roomForm['room_features'] ?? '') }}</textarea>
                                @error("rooms.$index.room_features")<span class="form-error">{{ $message }}</span>@enderror
                            </div>
                            @if($existingRoom && $roomImages->count())
                            <div class="form-group">
                                <label class="form-label">Current Room Images</label>
                                <div class="media-library">
                                    <div class="media-library-grid">
                                        @foreach($roomImages as $image)
                                        <div class="media-card">
                                            <div class="media-card-frame">
                                                <img src="{{ $image->getUrl() }}" alt="{{ $image->alt_text }}">
                                                @if($image->is_primary)
                                                <span class="media-card-badge">Primary</span>
                                                @endif
                                            </div>
                                            <div class="media-card-actions">
                                                @if(!$image->is_primary)
                                                <button type="button" class="media-card-action primary" onclick="setPrimaryRoomImage({{ $property->id }}, {{ $existingRoom->id }}, {{ $image->id }})">Set Primary</button>
                                                @endif
                                                <button type="button" class="media-card-action danger" onclick="deleteRoomImage({{ $property->id }}, {{ $existingRoom->id }}, {{ $image->id }})">Delete Image</button>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="form-group">
                                <label class="form-label">Add New Room Images</label>
                                <div class="upload-stack">
                                    <div class="upload-frame">
                                        <input type="file" name="rooms[{{ $index }}][images][]" class="form-input" multiple accept="image/jpeg,image/png,image/webp">
                                    </div>
                                    @error("rooms.$index.images.*")<span class="form-error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3 class="form-section-title">Property Images</h3>
                <p class="form-section-muted">Review existing images, mark a primary image, or upload more photos.</p>
                @if($property->images->count() > 0)
                <div class="form-group">
                    <label class="form-label">Current Images</label>
                    <div class="media-library">
                        <div class="media-library-grid">
                            @foreach($property->orderedImages as $image)
                            <div class="media-card">
                                <div class="media-card-frame">
                                    <img src="{{ $image->getUrl() }}" alt="{{ $image->alt_text }}">
                                    @if($image->is_primary)
                                    <span class="media-card-badge">Primary</span>
                                    @endif
                                </div>
                                <div class="media-card-actions">
                                    @if(!$image->is_primary)
                                    <button type="button" class="media-card-action primary" onclick="setPrimaryImage({{ $property->id }}, {{ $image->id }})">Set Primary</button>
                                    @endif
                                    <button type="button" class="media-card-action danger" onclick="deleteImage({{ $property->id }}, {{ $image->id }})">Delete</button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
                <div class="form-group">
                    <label for="images" class="form-label">Upload Additional Images</label>
                    <div class="upload-stack">
                        <div class="upload-frame">
                            <input type="file" name="images[]" id="images" class="form-input @error('images.*') error @enderror" multiple accept="image/jpeg,image/png,image/webp">
                        </div>
                    </div>
                    @error('images.*')<span class="form-error">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('owner.listings.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Update Property</button>
        </div>
    </form>
</div>

<form id="delete-room-form" method="POST" style="display: none;">@csrf @method('DELETE')</form>
<form id="delete-image-form" method="POST" style="display: none;">@csrf @method('DELETE')</form>
<form id="set-primary-image-form" method="POST" style="display: none;">@csrf @method('PATCH')</form>
<form id="delete-room-image-form" method="POST" style="display: none;">@csrf @method('DELETE')</form>
<form id="set-primary-room-image-form" method="POST" style="display: none;">@csrf @method('PATCH')</form>

<script>
    let nextRoomIndex = {{ count($roomForms) }};

    function toggleRentalSections() {
        const rentalMode = document.getElementById('rental_mode').value;
        const roomsSection = document.getElementById('rooms-management-section');
        const rentPriceGroup = document.getElementById('rent-price-group');
        const rentPriceInput = document.getElementById('rent_price');

        if (rentalMode === 'full_property') {
            rentPriceGroup.style.display = 'block';
            rentPriceInput.setAttribute('required', 'required');
            roomsSection.style.display = 'none';
            return;
        }

        rentPriceGroup.style.display = 'none';
        rentPriceInput.removeAttribute('required');
        roomsSection.style.display = 'block';
    }

    function refreshRoomsState() {
        const cards = document.querySelectorAll('#rooms-container .room-card');
        const emptyState = document.getElementById('rooms-empty-state');
        emptyState.style.display = cards.length > 0 ? 'none' : 'block';
        cards.forEach((card, index) => {
            const title = card.querySelector('[data-room-title]');
            if (title) title.textContent = `Room ${index + 1}`;
        });
    }

    function deleteExistingRoom(roomId) {
        if (!confirm('Are you sure you want to delete this room?')) return;
        const form = document.getElementById('delete-room-form');
        form.action = `/owner/listings/{{ $property->id }}/rooms/${roomId}`;
        form.submit();
    }

    function deleteImage(propertyId, imageId) {
        if (!confirm('Are you sure you want to delete this image?')) return;
        const form = document.getElementById('delete-image-form');
        form.action = `/owner/listings/${propertyId}/images/${imageId}`;
        form.submit();
    }

    function setPrimaryImage(propertyId, imageId) {
        const form = document.getElementById('set-primary-image-form');
        form.action = `/owner/listings/${propertyId}/images/${imageId}/primary`;
        form.submit();
    }

    function deleteRoomImage(propertyId, roomId, imageId) {
        if (!confirm('Are you sure you want to delete this room image?')) return;
        const form = document.getElementById('delete-room-image-form');
        form.action = `/owner/listings/${propertyId}/rooms/${roomId}/images/${imageId}`;
        form.submit();
    }

    function setPrimaryRoomImage(propertyId, roomId, imageId) {
        const form = document.getElementById('set-primary-room-image-form');
        form.action = `/owner/listings/${propertyId}/rooms/${roomId}/images/${imageId}/primary`;
        form.submit();
    }

    function removeNewRoomCard(button) {
        const card = button.closest('.room-card');
        if (card) card.remove();
        refreshRoomsState();
    }

    function addRoomCard() {
        const index = nextRoomIndex++;
        const container = document.getElementById('rooms-container');
        container.insertAdjacentHTML('beforeend', `
            <div class="room-card">
                <div class="room-card-header">
                    <div>
                        <h4 class="room-card-title" data-room-title>Room</h4>
                        <p class="room-card-status">New room</p>
                    </div>
                    <button type="button" class="room-card-action" onclick="removeNewRoomCard(this)">Remove</button>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Room Name *</label>
                        <input type="text" name="rooms[${index}][room_name]" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Room Number</label>
                        <input type="text" name="rooms[${index}][room_number]" class="form-input">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Capacity *</label>
                        <input type="number" name="rooms[${index}][capacity]" class="form-input" min="1" max="100" value="1" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Price per Month (NPR) *</label>
                        <input type="number" name="rooms[${index}][price]" class="form-input" min="0" step="0.01" required>
                    </div>
                    <div class="form-group flex items-center">
                        <label class="form-checkbox">
                            <input type="hidden" name="rooms[${index}][availability]" value="0">
                            <input type="checkbox" name="rooms[${index}][availability]" value="1" checked>
                            <span>Available for Booking</span>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Room Features</label>
                    <textarea name="rooms[${index}][room_features]" rows="3" class="form-input"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Add New Room Images</label>
                    <div class="upload-stack">
                        <div class="upload-frame">
                            <input type="file" name="rooms[${index}][images][]" class="form-input" multiple accept="image/jpeg,image/png,image/webp">
                        </div>
                    </div>
                </div>
            </div>
        `);
        refreshRoomsState();
    }

    document.addEventListener('DOMContentLoaded', function () {
        toggleRentalSections();
        refreshRoomsState();
    });
</script>
@endsection
