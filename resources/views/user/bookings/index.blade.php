@extends('user.layout')

@section('title', 'My Bookings')
@section('page-title', 'My Bookings')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div class="space-y-2">
            <p class="text-sm text-slate-500">Manage and track all your accommodation bookings</p>
            <p class="text-sm text-slate-600">
                <span class="font-semibold text-slate-900">{{ $bookings->total() }}</span>
                {{ $bookings->total() === 1 ? 'booking' : 'bookings' }}
            </p>
        </div>

        <a href="{{ route('listings.index') }}" class="inline-flex items-center justify-center rounded-xl bg-rose-500 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-600">
            {{ __('Browse Properties') }}
        </a>
    </div>

    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <p class="mb-1 font-semibold">{{ __('Error') }}</p>
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if (session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-[22px] border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
        @if($bookings->count() > 0)
            <div class="space-y-3">
                @foreach($bookings as $booking)
                    @php
                        $property = $booking->property;
                        $propertyTitle = $property->title ?? $property->property_name ?? 'Property';
                        $propertyCity = $property->city ?? ($property->location ?? 'Location not specified');
                        $propertyImage = $property && method_exists($property, 'getFirstImageUrl')
                            ? $property->getFirstImageUrl()
                            : 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=240&h=240&fit=crop';

                        if ($booking->isConfirmed()) {
                            $statusClasses = 'bg-emerald-50 text-emerald-700 border border-emerald-100';
                            $statusLabel = 'Confirmed';
                        } elseif ($booking->isPending()) {
                            $statusClasses = 'bg-blue-50 text-blue-700 border border-blue-100';
                            $statusLabel = 'Pending';
                        } elseif ($booking->isCancelled()) {
                            $statusClasses = 'bg-rose-50 text-rose-700 border border-rose-100';
                            $statusLabel = 'Cancelled';
                        } elseif ($booking->isCompleted()) {
                            $statusClasses = 'bg-slate-100 text-slate-700 border border-slate-200';
                            $statusLabel = 'Completed';
                        } elseif ($booking->isRejected()) {
                            $statusClasses = 'bg-amber-50 text-amber-700 border border-amber-100';
                            $statusLabel = 'Rejected';
                        } else {
                            $statusClasses = 'bg-slate-100 text-slate-700 border border-slate-200';
                            $statusLabel = ucfirst($booking->status ?? 'Status');
                        }
                    @endphp

                    <div class="rounded-[18px] border border-slate-200 bg-white px-3 py-3 sm:px-4">
                        <div class="grid grid-cols-1 gap-3 lg:grid-cols-[minmax(0,1.7fr)_150px_190px_160px] lg:items-center">
                            <div class="min-w-0">
                                <div class="flex items-start gap-3">
                                    <a href="{{ route('listings.show', $booking->property->id) }}" class="block h-16 w-16 shrink-0 overflow-hidden rounded-xl bg-slate-100">
                                        <img src="{{ $propertyImage }}"
                                            alt="{{ $propertyTitle }}"
                                            class="h-full w-full object-cover"
                                            onerror="this.src='https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=240&h=240&fit=crop'">
                                    </a>

                                    <div class="min-w-0 space-y-1.5">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $statusClasses }}">
                                                {{ $statusLabel }}
                                            </span>

                                            @if($booking->isRoomSpecific())
                                                <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-semibold text-blue-700 border border-blue-100">
                                                    Room: {{ $booking->room->room_name }}
                                                </span>
                                            @else
                                                <span class="inline-flex rounded-full bg-violet-50 px-2.5 py-1 text-[11px] font-semibold text-violet-700 border border-violet-100">
                                                    Full Property
                                                </span>
                                            @endif
                                        </div>

                                        <div>
                                            <a href="{{ route('listings.show', $booking->property->id) }}" class="block truncate text-sm font-semibold text-slate-900 hover:text-rose-500">
                                                {{ $propertyTitle }}
                                            </a>
                                            <p class="text-sm text-slate-500">{{ $propertyCity }}</p>
                                        </div>

                                        @if($booking->isRejected() && $booking->rejection_reason)
                                            <p class="text-xs text-rose-600">
                                                <strong>{{ __('Reason:') }}</strong> {{ $booking->rejection_reason }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-xl bg-slate-50 px-3 py-2.5">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-400">Check-in</p>
                                <p class="mt-1 text-sm font-semibold text-slate-900">{{ $booking->check_in_date->format('M d') }}</p>
                                <p class="text-xs text-slate-500">{{ $booking->getDurationInDays() }} days</p>
                            </div>

                            <div class="rounded-xl bg-slate-50 px-3 py-2.5">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-400">Total Rent</p>
                                <p class="mt-1 text-base font-bold text-rose-500">@npr($booking->total_rent)</p>
                                <div class="mt-1.5 space-y-0.5 text-xs">
                                    <p class="text-slate-500">Paid: <span class="font-medium text-slate-700">@npr($booking->getTotalPaid())</span></p>
                                    <p class="text-amber-600">Due: <span class="font-medium">@npr($booking->getAmountPending())</span></p>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-3 lg:justify-end">
                                <a href="{{ route('user.bookings.show', $booking) }}" class="text-sm font-semibold text-slate-700 hover:text-slate-900">
                                    {{ __('View') }}
                                </a>

                                @if($booking->isPending())
                                    <a href="{{ route('user.bookings.edit', $booking) }}" class="text-sm font-semibold text-slate-700 hover:text-slate-900">
                                        {{ __('Edit') }}
                                    </a>
                                @endif

                                @if($booking->isActive() || $booking->isPending() || $booking->isConfirmed())
                                    <form action="{{ route('user.bookings.cancel', $booking) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="text-sm font-semibold text-rose-600 hover:text-rose-700">
                                            {{ __('Cancel') }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 border-t border-slate-200 pt-5">
                {{ $bookings->links() }}
            </div>
        @else
            <div class="py-12 text-center">
                <svg class="mx-auto mb-5 h-16 w-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="mb-2 text-lg font-bold text-slate-900">{{ __('No Bookings Yet') }}</h3>
                <p class="mx-auto mb-5 max-w-sm text-sm leading-7 text-slate-500">
                    {{ __('Start your journey by exploring available properties and making your first booking.') }}
                </p>
                <a href="{{ route('listings.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-rose-500 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    {{ __('Explore Properties') }}
                </a>
            </div>
        @endif
    </div>
</div>
@endsection