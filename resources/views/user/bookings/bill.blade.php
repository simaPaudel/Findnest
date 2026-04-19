@extends('user.layout')

@section('title', 'Payment Invoice')
@section('page-title', 'Payment Invoice')

@push('styles')
<style>
    @page {
        size: A4;
        margin: 14mm;
    }

    @media print {
        .fn-navbar,
        .fn-alert,
        .no-print {
            display: none !important;
        }

        body,
        main {
            background: #fff !important;
        }

        main {
            padding: 0 !important;
        }

        .invoice-print-shell {
            max-width: none !important;
            margin: 0 !important;
        }

        .invoice-card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endpush

@section('content')
@php
    $propertyImageUrl = $booking->room?->getFirstImageUrl() ?? $booking->property?->getFirstImageUrl() ?? asset('images/property-placeholder.jpg');
    $bookedItemLabel = $booking->isRoomSpecific() && $booking->room
        ? $booking->room->room_name
        : 'Entire property';
    $totalPaid = (float) $booking->getTotalPaid();
    $advanceTarget = round((float) $booking->total_rent * 0.20, 2);
    $payableNow = max($advanceTarget - $totalPaid, 0);
    $remainingBalance = max((float) $booking->total_rent - $totalPaid, 0);
    $lastPayment = $booking->lastSuccessfulPayment();
    $bookingReadyLabel = $booking->isRoomSpecific() ? 'Room booked' : 'Property booked';

    if ($booking->isFullyPaid()) {
        $paymentHeadline = 'Fully paid';
        $paymentNote = 'All booking charges recorded successfully.';
        $accentClasses = 'bg-emerald-50 text-emerald-700 border-emerald-200';
    } elseif ($booking->hasSuccessfulPayment()) {
        $paymentHeadline = 'Advance payment done';
        $paymentNote = 'Your booking has been placed. Remaining balance can be settled later.';
        $accentClasses = 'bg-emerald-50 text-emerald-700 border-emerald-200';
    } else {
        $paymentHeadline = 'Advance payment required';
        $paymentNote = 'Pay 20% of the first month rent to place this booking.';
        $accentClasses = 'bg-amber-50 text-amber-700 border-amber-200';
    }
@endphp

<div class="invoice-print-shell max-w-5xl mx-auto">
    <div class="invoice-card bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-6 py-5 border-b border-slate-200">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-2">
                    <x-findnest-logo variant="inline" size="sm" />
                    <h2 class="text-2xl font-semibold text-slate-900">Monthly Rental Invoice</h2>
                    <div class="flex flex-wrap items-center gap-3 text-sm text-slate-500">
                        <span>Reference #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</span>
                        <span class="text-slate-300">|</span>
                        <span>Issued {{ $booking->created_at->format('M d, Y') }}</span>
                    </div>
                </div>

                <div class="min-w-[240px] rounded-2xl border px-5 py-4 {{ $accentClasses }}">
                    <p class="text-xs font-semibold uppercase tracking-wide">Status</p>
                    <p class="mt-2 text-lg font-semibold">{{ $paymentHeadline }}</p>
                    <p class="mt-1 text-sm leading-6">{{ $paymentNote }}</p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-[1.6fr_0.95fr]">
                <div class="space-y-6">
                    <section class="border border-slate-200 rounded-2xl overflow-hidden">
                        <div class="grid grid-cols-1 md:grid-cols-[220px_1fr]">
                            <div class="h-56 bg-slate-100">
                                <img
                                    src="{{ $propertyImageUrl }}"
                                    alt="{{ $booking->property->title }}"
                                    class="h-full w-full object-cover"
                                    onerror="this.src='{{ asset('images/property-placeholder.jpg') }}'">
                            </div>
                            <div class="p-5">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                                        {{ $booking->property->getRentalModeLabel() }}
                                    </span>
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                                        {{ $booking->property->getPropertyTypeLabel() }}
                                    </span>
                                </div>

                                <h3 class="mt-4 text-xl font-semibold text-slate-900">{{ $booking->property->title }}</h3>
                                <p class="mt-2 text-sm leading-6 text-slate-500">
                                    {{ $booking->property->address ?: ($booking->property->location ?: $booking->property->city) }}
                                    @if($booking->property->city)
                                        , {{ $booking->property->city }}
                                    @endif
                                </p>

                                <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-3">
                                    <div class="rounded-xl border border-slate-200 p-4">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Booked Unit</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-900">{{ $bookedItemLabel }}</p>
                                    </div>
                                    <div class="rounded-xl border border-slate-200 p-4">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Move-in Date</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-900">{{ $booking->check_in_date->format('M d, Y') }}</p>
                                    </div>
                                    <div class="rounded-xl border border-slate-200 p-4">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Booking Term</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-900">1 month</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="border border-slate-200 rounded-2xl overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-200">
                            <h3 class="text-base font-semibold text-slate-900">Invoice Summary</h3>
                        </div>

                        <div class="divide-y divide-slate-200">
                            <div class="flex items-center justify-between gap-4 px-5 py-4 text-sm">
                                <div>
                                    <p class="font-medium text-slate-900">One month rent</p>
                                    <p class="mt-1 text-slate-500">Base rent for the first month of the booking</p>
                                </div>
                                <p class="font-semibold text-slate-900">@npr($booking->total_rent)</p>
                            </div>
                            <div class="flex items-center justify-between gap-4 px-5 py-4 text-sm">
                                <div>
                                    <p class="font-medium text-slate-900">Advance required now</p>
                                    <p class="mt-1 text-slate-500">20% of the monthly rent</p>
                                </div>
                                <p class="font-semibold text-slate-900">@npr($advanceTarget)</p>
                            </div>
                            <div class="flex items-center justify-between gap-4 px-5 py-4 text-sm">
                                <div>
                                    <p class="font-medium text-slate-900">Advance received</p>
                                    <p class="mt-1 text-slate-500">Successful payment recorded for this booking</p>
                                </div>
                                <p class="font-semibold text-emerald-600">@npr($totalPaid)</p>
                            </div>
                            <div class="flex items-center justify-between gap-4 px-5 py-4 text-sm">
                                <div>
                                    <p class="font-medium text-slate-900">Remaining balance</p>
                                    <p class="mt-1 text-slate-500">Outstanding amount after advance payment</p>
                                </div>
                                <p class="font-semibold text-slate-900">@npr($remainingBalance)</p>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="space-y-6">
                    <section class="border border-slate-200 rounded-2xl p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Booking Status</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">
                            {{ $booking->hasSuccessfulPayment() ? $bookingReadyLabel : 'Waiting for advance payment' }}
                        </p>
                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            @if($booking->hasSuccessfulPayment())
                                {{ $bookedItemLabel }} has been reserved. The remaining balance is @npr($remainingBalance).
                            @else
                                Pay @npr($payableNow) now to place this booking and reserve {{ strtolower($bookedItemLabel) }}.
                            @endif
                        </p>
                    </section>

                    <section class="border border-slate-200 rounded-2xl p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Payment Details</p>
                        <dl class="mt-4 space-y-3 text-sm">
                            <div class="flex items-center justify-between gap-4">
                                <dt class="text-slate-500">Payable now</dt>
                                <dd class="font-semibold text-slate-900">@npr($payableNow)</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <dt class="text-slate-500">Payment method</dt>
                                <dd class="font-semibold text-slate-900">Khalti</dd>
                            </div>
                            @if($lastPayment)
                                <div class="flex items-center justify-between gap-4">
                                    <dt class="text-slate-500">Last payment</dt>
                                    <dd class="font-semibold text-slate-900">{{ $lastPayment->paid_at?->format('M d, Y') ?? $lastPayment->created_at->format('M d, Y') }}</dd>
                                </div>
                                <div class="flex items-center justify-between gap-4">
                                    <dt class="text-slate-500">Transaction</dt>
                                    <dd class="font-semibold text-slate-900">{{ $lastPayment->transaction_id ?: 'Recorded' }}</dd>
                                </div>
                            @endif
                        </dl>
                    </section>

                    <section class="border border-slate-200 rounded-2xl p-5 bg-slate-50">
                        <p class="text-sm font-medium text-slate-900">What this invoice means</p>
                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            This page shows the first-month rent, the 20% advance collected now, and the remaining amount left on the booking.
                        </p>
                    </section>
                </div>
            </div>
        </div>

        <div class="no-print flex flex-col gap-3 border-t border-slate-200 px-6 py-5 sm:flex-row">
            <a href="{{ route('user.bookings.show', $booking) }}" class="inline-flex flex-1 items-center justify-center rounded-xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                View Booking Details
            </a>

            @if($payableNow > 0)
                <a href="{{ route('payment.khalti.initiate', $booking->id) }}" class="inline-flex flex-1 items-center justify-center rounded-xl bg-rose-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-rose-700">
                    Pay @npr($payableNow)
                </a>
            @else
                <a href="{{ route('user.bookings.download-invoice', $booking) }}" class="inline-flex flex-1 items-center justify-center rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                    Download Invoice PDF
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
