@extends('owner.layout')

@section('title', 'Edit Property')
@section('page-title', 'Edit Property')

@section('content')
<div class="content-card">
    <form method="POST" action="{{ route('owner.listings.update', $property->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <!-- Basic Information -->
            <div class="form-section">
                <h3 class="form-section-title">Basic Information</h3>

                <div class="form-group">
                    <label for="title" class="form-label">Property Title *</label>
                    <input type="text" name="title" id="title" class="form-input @error('title') error @enderror" value="{{ old('title', $property->title) }}" required>
                    @error('title')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" rows="4" class="form-input @error('description') error @enderror">{{ old('description', $property->description) }}</textarea>
                    @error('description')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="room_type" class="form-label">Room Type *</label>
                        <select name="room_type" id="room_type" class="form-input @error('room_type') error @enderror" required>
                            <option value="">Select Type</option>
                            <option value="single" {{ old('room_type', $property->room_type) === 'single' ? 'selected' : '' }}>Single Room</option>
                            <option value="shared" {{ old('room_type', $property->room_type) === 'shared' ? 'selected' : '' }}>Shared Room</option>
                            <option value="flat" {{ old('room_type', $property->room_type) === 'flat' ? 'selected' : '' }}>Flat/Apartment</option>
                        </select>
                        @error('room_type')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="rent_price" class="form-label">Rent Price/Month (NPR) *</label>
                        <input type="number" name="rent_price" id="rent_price" class="form-input @error('rent_price') error @enderror" value="{{ old('rent_price', $property->rent_price) }}" min="0" step="0.01" required>
                        @error('rent_price')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div class="form-section">
                <h3 class="form-section-title">Location Details</h3>

                <div class="form-group">
                    <label for="address" class="form-label">Address *</label>
                    <input type="text" name="address" id="address" class="form-input @error('address') error @enderror" value="{{ old('address', $property->address) }}" required>
                    @error('address')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="city" class="form-label">City *</label>
                        <input type="text" name="city" id="city" class="form-input @error('city') error @enderror" value="{{ old('city', $property->city) }}" required>
                        @error('city')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="location" class="form-label">Location/Area</label>
                        <input type="text" name="location" id="location" class="form-input @error('location') error @enderror" value="{{ old('location', $property->location) }}">
                        @error('location')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="landmark" class="form-label">Landmark</label>
                    <input type="text" name="landmark" id="landmark" class="form-input @error('landmark') error @enderror" value="{{ old('landmark', $property->landmark) }}">
                    @error('landmark')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="latitude" class="form-label">Latitude</label>
                        <input type="number" name="latitude" id="latitude" class="form-input @error('latitude') error @enderror" value="{{ old('latitude', $property->latitude) }}" step="any">
                        @error('latitude')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="longitude" class="form-label">Longitude</label>
                        <input type="number" name="longitude" id="longitude" class="form-input @error('longitude') error @enderror" value="{{ old('longitude', $property->longitude) }}" step="any">
                        @error('longitude')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Property Details -->
            <div class="form-section">
                <h3 class="form-section-title">Property Details</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="total_rooms" class="form-label">Total Rooms</label>
                        <input type="number" name="total_rooms" id="total_rooms" class="form-input @error('total_rooms') error @enderror" value="{{ old('total_rooms', $property->total_rooms) }}" min="1">
                        @error('total_rooms')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="gender_preference" class="form-label">Gender Preference</label>
                        <select name="gender_preference" id="gender_preference" class="form-input @error('gender_preference') error @enderror">
                            <option value="any" {{ old('gender_preference', $property->gender_preference) === 'any' ? 'selected' : '' }}>Any</option>
                            <option value="male" {{ old('gender_preference', $property->gender_preference) === 'male' ? 'selected' : '' }}>Male Only</option>
                            <option value="female" {{ old('gender_preference', $property->gender_preference) === 'female' ? 'selected' : '' }}>Female Only</option>
                        </select>
                        @error('gender_preference')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-checkbox">
                        <input type="checkbox" name="furnished" value="1" {{ old('furnished', $property->furnished) ? 'checked' : '' }}>
                        <span>Furnished</span>
                    </label>
                </div>

                <div class="form-group">
                    <label for="amenities" class="form-label">Amenities (comma-separated)</label>
                    <input type="text" name="amenities" id="amenities" class="form-input @error('amenities') error @enderror" 
                        value="{{ old('amenities', is_array($property->amenities) ? implode(', ', $property->amenities) : '') }}" 
                        placeholder="e.g., WiFi, AC, Parking">
                    @error('amenities')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="rules" class="form-label">House Rules</label>
                    <textarea name="rules" id="rules" rows="3" class="form-input @error('rules') error @enderror">{{ old('rules', $property->rules) }}</textarea>
                    @error('rules')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Photos -->
            <div class="form-section">
                <h3 class="form-section-title">Property Photos</h3>

                @php
                    // Handle both JSON strings and arrays
                    $photos = $property->photos;
                    if (is_string($photos)) {
                        $photos = json_decode($photos, true) ?? [];
                    }
                    $photos = $photos ?? [];
                @endphp

                @if(!empty($photos))
                    <div class="form-group">
                        <label class="form-label">Current Photos</label>
                        <div class="photo-grid">
                            @foreach($photos as $photo)
                                <img src="{{ asset('storage/' . $photo) }}" alt="Property Photo" class="photo-thumbnail">
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="form-group">
                    <label for="photos" class="form-label">Upload Additional Photos (max 2MB each)</label>
                    <input type="file" name="photos[]" id="photos" class="form-input @error('photos.*') error @enderror" multiple accept="image/jpeg,image/png,image/jpg,image/webp">
                    <small class="form-hint">New photos will be added to existing ones. Accepted formats: JPG, PNG, WEBP</small>
                    @error('photos.*')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('owner.listings.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Update Property</button>
        </div>
    </form>
</div>
@endsection
