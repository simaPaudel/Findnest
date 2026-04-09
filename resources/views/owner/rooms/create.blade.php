@extends('owner.layout')

@section('title', 'Add New Room')
@section('page-title', "Add Room - {{ $property->title }}")

@section('content')
<div class="mb-6">
    <a href="{{ route('owner.rooms.index', $property) }}" class="text-blue-500 hover:text-blue-700">← Back to Rooms</a>
</div>

<div class="content-card">
    <form method="POST" action="{{ route('owner.rooms.store', $property) }}" enctype="multipart/form-data">
        @csrf

        <div class="form-grid">
            <!-- Room Information -->
            <div class="form-section">
                <h3 class="form-section-title">Room Information</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="room_name" class="form-label">Room Name *</label>
                        <input type="text" name="room_name" id="room_name" class="form-input @error('room_name') error @enderror" value="{{ old('room_name') }}" placeholder="e.g., Master Bedroom" required>
                        @error('room_name')
                        <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="room_number" class="form-label">Room Number</label>
                        <input type="text" name="room_number" id="room_number" class="form-input @error('room_number') error @enderror" value="{{ old('room_number') }}" placeholder="e.g., 101">
                        @error('room_number')
                        <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="capacity" class="form-label">Capacity (Persons) *</label>
                        <input type="number" name="capacity" id="capacity" class="form-input @error('capacity') error @enderror" value="{{ old('capacity', 1) }}" min="1" max="100" required>
                        @error('capacity')
                        <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="price" class="form-label">Price per Month (NPR) *</label>
                        <input type="number" name="price" id="price" class="form-input @error('price') error @enderror" value="{{ old('price') }}" min="0" step="0.01" required>
                        @error('price')
                        <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-checkbox">
                            <input type="checkbox" name="availability" value="1" {{ old('availability', true) ? 'checked' : '' }}>
                            <span>Available for Booking</span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="room_features" class="form-label">Room Features & Description</label>
                    <textarea name="room_features" id="room_features" rows="4" class="form-input @error('room_features') error @enderror" placeholder="e.g., Attached bathroom, Window with city view, Air conditioning">{{ old('room_features') }}</textarea>
                    @error('room_features')
                    <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Room Images -->
            <div class="form-section">
                <h3 class="form-section-title">Room Images</h3>

                <div class="form-group">
                    <label for="images" class="form-label">Upload Images</label>
                    <input type="file" name="images[]" id="images" class="form-input @error('images.*') error @enderror" multiple accept="image/jpeg,image/png,image/webp">
                    <small class="form-hint">You can select multiple images. Accepted formats: JPG, PNG, WEBP. Max 5MB per file, up to 10 files.</small>
                    @error('images')
                    <span class="form-error">{{ $message }}</span>
                    @enderror
                    @error('images.*')
                    <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Property Information -->
            <div class="form-section">
                <h3 class="form-section-title">Property Information</h3>
                <div class="space-y-2 text-sm">
                    <div>
                        <p class="text-gray-600">Property</p>
                        <p class="font-semibold">{{ $property->title }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Address</p>
                        <p class="font-semibold">{{ $property->address }}, {{ $property->city }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Rental Mode</p>
                        <p class="font-semibold">
                            @if($property->rental_mode === 'full_property')
                            Full Property Only
                            @else
                            Per Room
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('owner.rooms.index', $property) }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Create Room</button>
        </div>
    </form>
</div>
@endsection
