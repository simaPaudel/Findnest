@extends('user.layout')

@section('title', 'Payment Invoice')
@section('page-title', 'Payment Invoice')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Invoice Container -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-8 border-b border-gray-200">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-1">INVOICE</h1>
                    <p class="text-sm text-gray-500">Booking ID: <span class="font-semibold text-gray-700">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</span></p>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold text-red-600">FindNest</p>
                    <p class="text-sm text-gray-500 mt-1">Payment Due</p>
                </div>
            </div>

            <!-- Date & Terms Row -->
            <div class="grid grid-cols-2 gap-8">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Invoice Date</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $booking->created_at->format('M d, Y') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Payment Terms</p>
                    <p class="text-lg font-semibold text-gray-900">Advance Payment (20%)</p>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="p-8">
            <!-- Property Details -->
            <div class="mb-8 pb-8 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Property Details</h3>
                <div class="flex gap-4">
                    @php
                        $firstPhoto = !empty($booking->property->photos) ? $booking->property->photos[0] : null;
                    @endphp
                    @if($firstPhoto)
                        <img src="{{ asset('storage/' . $firstPhoto) }}" 
                             alt="{{ $booking->property->title }}" 
                             class="w-24 h-24 rounded-lg object-cover"
                             onerror="this.src='https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=100&h=100&fit=crop'">
                    @else
                        <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=100&h=100&fit=crop" 
                             alt="{{ $booking->property->title }}" 
                             class="w-24 h-24 rounded-lg object-cover">
                    @endif
                    <div>
                        <h4 class="text-lg font-bold text-gray-900 mb-1">{{ $booking->property->title }}</h4>
                        <p class="text-sm text-gray-500 mb-1">{{ $booking->property->location }}, {{ $booking->property->city }}</p>
                        <p class="text-sm text-blue-600 font-medium">{{ ucfirst($booking->property->room_type) }} Room</p>
                    </div>
                </div>
            </div>

            <!-- Dates Row -->
            <div class="grid grid-cols-2 gap-8 mb-8 pb-8 border-b border-gray-200">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Move-in Date</p>
                    <p class="text-lg font-bold text-gray-900">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Booking Duration</p>
                    <p class="text-lg font-bold text-gray-900">{{ $booking->duration_months }} Month{{ $booking->duration_months > 1 ? 's' : '' }}</p>
                </div>
            </div>

            <!-- Charges Section -->
            <div class="mb-8 pb-8 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Charges</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-medium text-gray-900">Monthly Rent</p>
                            <p class="text-sm text-gray-500">@npr($booking->property->rent_price) × {{ $booking->duration_months }} month{{ $booking->duration_months > 1 ? 's' : '' }}</p>
                        </div>
                        <p class="font-semibold text-gray-900">@npr($booking->total_rent)</p>
                    </div>
                </div>
            </div>

            <!-- Total Amount -->
            <div class="mb-8 flex justify-between items-center pb-8 border-b border-gray-200">
                <p class="text-lg font-bold text-gray-900">Total Amount</p>
                <p class="text-3xl font-bold text-red-600">@npr($booking->total_rent)</p>
            </div>

            <!-- Advance Payment Box -->
            <div class="mb-8 bg-red-50 border-2 border-red-200 rounded-xl p-6">
                <div class="flex items-start gap-3 mb-4">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900 mb-1">Advance Payment Required</h3>
                        <p class="text-sm text-gray-600">Pay now to confirm your booking. The remaining 80% will be due before check-in.</p>
                    </div>
                </div>
                <div class="bg-white rounded-lg p-4 mt-4">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-gray-900">Amount to Pay (20%)</span>
                        <span class="text-2xl font-bold text-red-600">@npr($booking->total_rent * 0.20)</span>
                    </div>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-8 flex items-center gap-3">
                <svg class="w-5 h-5 text-yellow-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="font-semibold text-gray-900">Pending Payment</p>
                    <p class="text-xs text-gray-600">Booking will be confirmed after payment</p>
                </div>
            </div>
        </div>

        <!-- Footer with Buttons -->
        <div class="bg-gray-50 px-8 py-6 border-t border-gray-200 flex gap-3">
            <a href="{{ route('listings.show', $booking->property->id) }}" class="flex-1 px-6 py-3 border-2 border-gray-300 rounded-lg text-center font-semibold text-gray-700 hover:bg-gray-100 transition">
                ← Go Back
            </a>
            <a href="{{ route('payment.khalti.initiate', $booking->id) }}" class="flex-1 px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg text-center font-semibold transition shadow-md">
                💳 Pay @npr($booking->total_rent * 0.20) Now
            </a>
        </div>

        <!-- Footer Text -->
        <div class="text-center py-4 text-xs text-gray-500 bg-gray-50 border-t border-gray-200">
            Questions? Contact us at support@findnest.com
        </div>
    </div>
</div>

<style>
    @media print {
        .shadow-lg {
            box-shadow: none;
        }
    }
</style>
@endsection
