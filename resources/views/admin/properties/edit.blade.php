@extends('admin.layout')

@section('title', $property->title . ' | Edit Property')
@section('page_kicker', 'Properties')
@section('page_title', 'Edit Property')
@section('page_meta', 'Admin property management')

@section('content')
    @php
        $statusLabel = ucfirst((string) $property->status);
        $verificationLabel = $property->is_verified ? 'Verified' : 'Unverified';
        $selectedAmenities = old('amenity_ids', $property->amenities->pluck('id')->all());
        $selectedAmenities = is_array($selectedAmenities) ? $selectedAmenities : [];
        $rooms = $property->rooms ?? collect();
        $roomCount = $rooms->count();
        $availableRoomCount = $rooms->where('availability', true)->count();
    @endphp

    <div class="admin-dashboard admin-properties-page admin-edit-page">
        <section class="content-card admin-edit-hero-card">
            <div class="admin-edit-hero-top">
                <div class="admin-edit-hero-copy">
                    <p class="admin-section-label">Admin edit</p>
                    <h2>{{ $property->title }}</h2>
                    <p class="admin-edit-note">
                        Update the listing details from the admin panel. Room and booking controls stay on the property review pages.
                    </p>
                </div>

                <div class="admin-edit-badges">
                    <span class="status-pill status-neutral">{{ $statusLabel }}</span>
                    <span class="status-pill {{ $property->is_verified ? 'status-approved' : 'status-neutral' }}">{{ $verificationLabel }}</span>
                    <span class="status-pill status-neutral">{{ $property->getPropertyTypeLabel() }}</span>
                    <span class="status-pill status-neutral">{{ $property->getRentalModeLabel() }}</span>
                </div>
            </div>
        </section>

        <div class="admin-edit-layout">
            <div class="admin-edit-main">
                <form method="POST" action="{{ route('admin.properties.update', $property) }}" id="admin-property-edit-form">
                    @csrf
                    @method('PUT')

                    <section class="content-card">
                        <div class="content-card-header admin-panel-header">
                            <div>
                                <h2>Listing Details</h2>
                                <p>Core information shown to renters and admins.</p>
                            </div>
                        </div>

                        <div class="admin-edit-form-grid">
                            <div class="admin-edit-field admin-edit-field-wide">
                                <label for="title" class="admin-edit-label">Property Title *</label>
                                <input
                                    type="text"
                                    name="title"
                                    id="title"
                                    class="admin-input"
                                    value="{{ old('title', $property->title) }}"
                                    required
                                >
                            </div>

                            <div class="admin-edit-field admin-edit-field-wide">
                                <label for="description" class="admin-edit-label">Description</label>
                                <textarea
                                    name="description"
                                    id="description"
                                    rows="4"
                                    class="admin-input admin-input-textarea"
                                >{{ old('description', $property->description) }}</textarea>
                            </div>
                        </div>
                    </section>

                    <section class="content-card">
                        <div class="content-card-header admin-panel-header">
                            <div>
                                <h2>Location</h2>
                                <p>Address and map coordinates for the listing.</p>
                            </div>
                        </div>

                        <div class="admin-edit-form-grid">
                            <div class="admin-edit-grid">
                                <div class="admin-edit-field">
                                    <label for="address" class="admin-edit-label">Address *</label>
                                    <input
                                        type="text"
                                        name="address"
                                        id="address"
                                        class="admin-input"
                                        value="{{ old('address', $property->address) }}"
                                        required
                                    >
                                </div>

                                <div class="admin-edit-field">
                                    <label for="city" class="admin-edit-label">City *</label>
                                    <input
                                        type="text"
                                        name="city"
                                        id="city"
                                        class="admin-input"
                                        value="{{ old('city', $property->city) }}"
                                        required
                                    >
                                </div>

                                <div class="admin-edit-field">
                                    <label for="location" class="admin-edit-label">Location / Area</label>
                                    <input
                                        type="text"
                                        name="location"
                                        id="location"
                                        class="admin-input"
                                        value="{{ old('location', $property->location) }}"
                                    >
                                </div>

                                <div class="admin-edit-field">
                                    <label for="landmark" class="admin-edit-label">Landmark</label>
                                    <input
                                        type="text"
                                        name="landmark"
                                        id="landmark"
                                        class="admin-input"
                                        value="{{ old('landmark', $property->landmark) }}"
                                    >
                                </div>

                                <div class="admin-edit-field">
                                    <label for="latitude" class="admin-edit-label">Latitude</label>
                                    <input
                                        type="number"
                                        name="latitude"
                                        id="latitude"
                                        class="admin-input"
                                        value="{{ old('latitude', $property->latitude) }}"
                                        step="any"
                                    >
                                </div>

                                <div class="admin-edit-field">
                                    <label for="longitude" class="admin-edit-label">Longitude</label>
                                    <input
                                        type="number"
                                        name="longitude"
                                        id="longitude"
                                        class="admin-input"
                                        value="{{ old('longitude', $property->longitude) }}"
                                        step="any"
                                    >
                                </div>
                            </div>

                            @include('components.leaflet-property-map', [
                                'mapId' => 'admin-edit-property-map',
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
                                'helpText' => 'Click the map to update the coordinates. The latitude and longitude fields stay in sync.',
                            ])
                        </div>
                    </section>

                    <section class="content-card">
                        <div class="content-card-header admin-panel-header">
                            <div>
                                <h2>Property Settings</h2>
                                <p>Listing type, rental mode, and pricing.</p>
                            </div>
                        </div>

                        <div class="admin-edit-form-grid">
                            <div class="admin-edit-grid">
                                <div class="admin-edit-field">
                                    <label for="property_type" class="admin-edit-label">Property Type *</label>
                                    <select name="property_type" id="property_type" class="admin-input" required>
                                        @foreach ($propertyTypes as $value => $label)
                                            <option value="{{ $value }}" {{ old('property_type', $property->property_type) === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="admin-edit-field">
                                    <label for="rental_mode" class="admin-edit-label">Rental Mode *</label>
                                    <select name="rental_mode" id="rental_mode" class="admin-input" required>
                                        @foreach ($rentalModes as $value => $label)
                                            <option value="{{ $value }}" {{ old('rental_mode', $property->rental_mode) === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="admin-edit-field">
                                    <label for="rent_price" class="admin-edit-label">Rent Price / Month (NPR)</label>
                                    <input
                                        type="number"
                                        name="rent_price"
                                        id="rent_price"
                                        class="admin-input"
                                        value="{{ old('rent_price', $property->rent_price) }}"
                                        min="0"
                                        step="0.01"
                                    >
                                </div>

                                <div class="admin-edit-field">
                                    <label for="total_rooms" class="admin-edit-label">Total Rooms</label>
                                    <input
                                        type="number"
                                        name="total_rooms"
                                        id="total_rooms"
                                        class="admin-input"
                                        value="{{ old('total_rooms', $property->total_rooms) }}"
                                        min="1"
                                    >
                                </div>

                                <div class="admin-edit-field">
                                    <label for="gender_preference" class="admin-edit-label">Gender Preference</label>
                                    <select name="gender_preference" id="gender_preference" class="admin-input">
                                        @foreach ($genderOptions as $value => $label)
                                            <option value="{{ $value }}" {{ old('gender_preference', $property->gender_preference ?? 'any') === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <label class="admin-edit-checkbox">
                                    <input type="hidden" name="furnished" value="0">
                                    <input type="checkbox" name="furnished" value="1" {{ old('furnished', $property->furnished) ? 'checked' : '' }}>
                                    <span>Furnished</span>
                                </label>
                            </div>

                            <p class="admin-edit-note">
                                Room entries are preserved as-is. Use the property review page for room-specific moderation actions.
                            </p>
                        </div>
                    </section>

                    <section class="content-card">
                        <div class="content-card-header admin-panel-header">
                            <div>
                                <h2>Amenities</h2>
                                <p>Select the facilities attached to this property.</p>
                            </div>
                        </div>

                        <div class="admin-edit-form-grid">
                            <div class="admin-edit-amenities-grid">
                                @foreach ($amenities as $amenity)
                                    <label class="admin-edit-amenity">
                                        <input
                                            type="checkbox"
                                            name="amenity_ids[]"
                                            value="{{ $amenity->id }}"
                                            {{ in_array($amenity->id, $selectedAmenities) ? 'checked' : '' }}
                                        >
                                        <span>{{ $amenity->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </section>

                    <section class="content-card">
                        <div class="content-card-header admin-panel-header">
                            <div>
                                <h2>House Rules</h2>
                                <p>Listing restrictions and notes.</p>
                            </div>
                        </div>

                        <div class="admin-property-section-body">
                            <textarea
                                name="rules"
                                id="rules"
                                rows="4"
                                class="admin-input admin-input-textarea"
                            >{{ old('rules', $property->rules) }}</textarea>
                        </div>
                    </section>
                </form>
            </div>

            <aside class="admin-edit-sidebar">
                <section class="content-card">
                    <div class="content-card-header admin-panel-header">
                        <div>
                            <h2>Snapshot</h2>
                            <p>Quick review before saving changes.</p>
                        </div>
                    </div>

                    <div class="admin-detail-list">
                        <div class="admin-detail-row">
                            <span class="admin-detail-label">Owner</span>
                            <span class="admin-detail-value">{{ $property->owner->name ?? 'N/A' }}</span>
                        </div>
                        <div class="admin-detail-row">
                            <span class="admin-detail-label">Rooms</span>
                            <span class="admin-detail-value">{{ $roomCount }} total</span>
                        </div>
                        <div class="admin-detail-row">
                            <span class="admin-detail-label">Available</span>
                            <span class="admin-detail-value">{{ $availableRoomCount }} rooms</span>
                        </div>
                        <div class="admin-detail-row">
                            <span class="admin-detail-label">Created</span>
                            <span class="admin-detail-value">{{ optional($property->created_at)->format('M d, Y') ?? 'N/A' }}</span>
                        </div>
                        <div class="admin-detail-row">
                            <span class="admin-detail-label">Updated</span>
                            <span class="admin-detail-value">{{ optional($property->updated_at)->format('M d, Y') ?? 'N/A' }}</span>
                        </div>
                    </div>
                </section>

                <section class="content-card">
                    <div class="content-card-header admin-panel-header">
                        <div>
                            <h2>Actions</h2>
                            <p>Save or exit without affecting the owner flow.</p>
                        </div>
                    </div>

                    <div class="admin-edit-actions">
                        <button type="submit" form="admin-property-edit-form" class="admin-btn admin-btn-primary">Save Changes</button>
                        <a href="{{ route('admin.properties.show', $property) }}" class="admin-btn admin-btn-secondary">Cancel</a>
                    </div>
                </section>
            </aside>
        </div>
    </div>
@endsection
