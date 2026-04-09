@extends('owner.layout')

@section('title', 'Manage Rooms')
@section('page-title', "Rooms - {{ $property->title }}")

@section('content')
<div class="mb-6">
    <a href="{{ route('owner.listings.index') }}" class="text-blue-500 hover:text-blue-700">← Back to Listings</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Rooms List -->
    <div class="lg:col-span-2">
        <div class="content-card">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Rooms for {{ $property->title }}</h2>
                <a href="{{ route('owner.rooms.create', $property) }}" class="btn-primary">+ Add New Room</a>
            </div>

            @if($rooms->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="px-4 py-3 text-left font-semibold">Room</th>
                            <th class="px-4 py-3 text-left font-semibold">Capacity</th>
                            <th class="px-4 py-3 text-left font-semibold">Price/Month</th>
                            <th class="px-4 py-3 text-left font-semibold">Booking Rule</th>
                            <th class="px-4 py-3 text-left font-semibold">Status</th>
                            <th class="px-4 py-3 text-left font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rooms as $room)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div>
                                    <h4 class="font-semibold">{{ $room->room_name }}</h4>
                                    <p class="text-xs text-gray-600">{{ $room->room_number ?? 'N/A' }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ $room->capacity }}</td>
                            <td class="px-4 py-3">NPR {{ number_format($room->price, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-gray-600">One confirmed booking locks this room</span>
                            </td>
                            <td class="px-4 py-3">
                                @if($room->availability)
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Available</span>
                                @else
                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded">Unavailable</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <a href="{{ route('owner.rooms.edit', [$property, $room]) }}" class="text-blue-500 hover:text-blue-700 text-sm font-medium">Edit</a>
                                    <form action="{{ route('owner.rooms.destroy', [$property, $room]) }}" method="POST" onsubmit="return confirm('Delete this room?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($rooms->hasPages())
            <div class="mt-6">
                {{ $rooms->links() }}
            </div>
            @endif
            @else
            <div class="text-center py-12">
                <p class="text-gray-600 mb-4">No rooms yet. Start by adding one!</p>
                <a href="{{ route('owner.rooms.create', $property) }}" class="btn-primary">+ Add First Room</a>
            </div>
            @endif
        </div>
    </div>

    <!-- Property Info -->
    <div class="lg:col-span-2">
        <div class="content-card">
            <h3 class="text-lg font-semibold mb-4">Property Information</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-600">Title</p>
                    <p class="font-semibold">{{ $property->title }}</p>
                </div>
                <div>
                    <p class="text-gray-600">City</p>
                    <p class="font-semibold">{{ $property->city }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Rental Mode</p>
                    <p class="font-semibold">
                        @if($property->rental_mode === 'full_property')
                        Full Property Only
                        @elseif($property->rental_mode === 'rooms')
                        Rooms Only
                        @else
                        Both (Full Property & Rooms)
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-gray-600">Status</p>
                    <p class="font-semibold">{{ ucfirst($property->status) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
