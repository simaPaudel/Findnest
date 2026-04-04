@extends('user.layout')

@section('title', 'Book This Property')
@section('page-title', 'Book This Property')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="fn-glass-card">
        <div class="mb-8 pb-8 border-b border-gray-200">
            <h2 class="text-2xl font-bold fn-text-charcoal mb-2">{{ $property->title }}</h2>
            <p class="fn-text-gray mb-4">{{ $property->city }}, {{ $property->location }}</p>
            
            <div class="flex items-center gap-4">
                @php
                    $firstPhoto = !empty($property->photos) ? $property->photos[0] : null;
                @endphp
                @if($firstPhoto)
                    <img src="{{ asset('storage/' . $firstPhoto) }}" 
                         alt="{{ $property->title }}" 
                         class="w-24 h-24 rounded-lg object-cover"
                         onerror="this.src='https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=100&h=100&fit=crop'">
                @else
                    <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=100&h=100&fit=crop" 
                         alt="{{ $property->title }}" 
                         class="w-24 h-24 rounded-lg object-cover">
                @endif
                <div>
                    <p class="text-sm fn-text-gray mb-1">Monthly Rent</p>
                    <p class="text-2xl font-bold fn-text-red">@npr($property->rent_price)</p>
                    <p class="text-sm fn-text-gray mt-2">{{ ucfirst($property->room_type) }} Room</p>
                </div>
            </div>
        </div>

        <form action="{{ route('user.bookings.create') }}" method="POST">
            @csrf
            <input type="hidden" name="property_id" value="{{ $property->id }}">

            <div class="space-y-6">
                <!-- Check-in Date -->
                <div>
                    <label class="block text-sm font-semibold fn-text-charcoal mb-2">
                        When do you want to move in? <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="check_in_date" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-lg"
                           value="{{ old('check_in_date') }}"
                           min="{{ now()->format('Y-m-d') }}"
                           required>
                    @error('check_in_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info box -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm fn-text-gray">
                        <svg class="w-5 h-5 inline mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"></path>
                        </svg>
                        You'll see the final bill with all details on the next page
                    </p>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-6">
                    <a href="{{ route('listings.show', $property->id) }}" class="fn-btn-secondary flex-1 text-center">
                        Cancel
                    </a>
                    <button type="submit" class="fn-btn-primary flex-1">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        Next: Review Invoice
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
