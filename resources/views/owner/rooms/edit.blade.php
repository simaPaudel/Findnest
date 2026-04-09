@extends('owner.layout')

@section('title', 'Edit Room')
@section('page-title', "Edit Room - {{ $property->title }}")

@section('content')
<div class="mb-6">
    <a href="{{ route('owner.rooms.index', $property) }}" class="text-blue-500 hover:text-blue-700">← Back to Rooms</a>
</div>

<div class="content-card">
    <form method="POST" action="{{ route('owner.rooms.update', [$property, $room]) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <!-- Room Information -->
            <div class="form-section">
                <h3 class="form-section-title">Room Information</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="room_name" class="form-label">Room Name *</label>
                        <input type="text" name="room_name" id="room_name" class="form-input @error('room_name') error @enderror" value="{{ old('room_name', $room->room_name) }}" placeholder="e.g., Master Bedroom" required>
                        @error('room_name')
                        <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="room_number" class="form-label">Room Number</label>
                        <input type="text" name="room_number" id="room_number" class="form-input @error('room_number') error @enderror" value="{{ old('room_number', $room->room_number) }}" placeholder="e.g., 101">
                        @error('room_number')
                        <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="capacity" class="form-label">Capacity (Persons) *</label>
                        <input type="number" name="capacity" id="capacity" class="form-input @error('capacity') error @enderror" value="{{ old('capacity', $room->capacity) }}" min="1" max="100" required>
                        @error('capacity')
                        <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="price" class="form-label">Price per Month (NPR) *</label>
                        <input type="number" name="price" id="price" class="form-input @error('price') error @enderror" value="{{ old('price', $room->price) }}" min="0" step="0.01" required>
                        @error('price')
                        <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-checkbox">
                            <input type="checkbox" name="availability" value="1" {{ old('availability', $room->availability) ? 'checked' : '' }}>
                            <span>Available for Booking</span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="room_features" class="form-label">Room Features & Description</label>
                    <textarea name="room_features" id="room_features" rows="4" class="form-input @error('room_features') error @enderror" placeholder="e.g., Attached bathroom, Window with city view, Air conditioning">{{ old('room_features', $room->room_features) }}</textarea>
                    @error('room_features')
                    <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Room Images -->
            <div class="form-section">
                <h3 class="form-section-title">Room Images</h3>

                @if($room->images()->exists())
                <div class="mb-6">
                    <h4 class="text-sm font-semibold mb-3">Current Images</h4>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($room->images()->ordered()->get() as $image)
                        <div class="relative group">
                            <img src="{{ $image->getUrl() }}" alt="{{ $image->alt_text }}" class="w-full h-32 object-cover rounded-lg">

                            @if($image->is_primary)
                            <div class="absolute top-2 left-2 bg-blue-500 text-white text-xs px-2 py-1 rounded">Primary</div>
                            @endif

                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 rounded-lg transition flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100">
                                @if(!$image->is_primary)
                                <form method="POST" action="{{ route('owner.rooms.set-primary-image', [$property, $room, $image]) }}" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs" title="Set as Primary">★</button>
                                </form>
                                @endif

                                <form method="POST" action="{{ route('owner.rooms.delete-image', [$property, $room, $image]) }}" style="display: inline;" onsubmit="return confirm('Delete this image?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">✕</button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="form-group">
                    <label for="images" class="form-label">Add More Images</label>
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
                    <div>
                        <p class="text-gray-600">Booking Status</p>
                        <p class="font-semibold">{{ $room->availability ? 'Available' : 'Booked' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('owner.rooms.index', $property) }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Update Room</button>
        </div>
    </form>

    <!-- Delete Room Section -->
    <div class="mt-8 pt-8 border-t border-red-200">
        <h3 class="text-lg font-semibold text-red-600 mb-4">Delete Room</h3>
        <p class="text-gray-600 mb-4">This action cannot be undone. Please be careful.</p>

        <form method="POST" action="{{ route('owner.rooms.destroy', [$property, $room]) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this room? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger">Delete Room</button>
        </form>
    </div>
</div>
@endsection
