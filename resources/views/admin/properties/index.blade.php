@extends('admin.layout')

@section('title', 'Properties')
@section('page_title', 'Properties')
@section('hide_pagebar', 'true')

@section('content')
<div class="admin-dashboard admin-properties-page">
    <section class="admin-properties-tabs" aria-label="Property listing tabs">
        <a
            href="{{ route('admin.properties.index', array_merge(request()->except(['page', 'tab', 'status']), ['tab' => 'current'])) }}"
            class="admin-properties-tab {{ $activeTab === 'current' ? 'active' : '' }}">
            Current Listings
            <span>{{ $currentListingsCount }}</span>
        </a>
        <a
            href="{{ route('admin.properties.index', array_merge(request()->except(['page', 'tab', 'status']), ['tab' => 'requests'])) }}"
            class="admin-properties-tab {{ $activeTab === 'requests' ? 'active' : '' }}">
            Listing Requests
            <span>{{ $listingRequestsCount }}</span>
        </a>
    </section>

    <div class="admin-properties-layout">
        <aside class="content-card admin-properties-sidebar">
            <div class="content-card-header admin-properties-sidebar-header">
                <div>
                    <p class="admin-section-label">Filters</p>
                    <h2>Refine properties</h2>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.properties.index') }}" class="admin-properties-filter-form">
                <input type="hidden" name="tab" value="{{ $activeTab }}">

                <div class="admin-properties-filter-group">
                    <label for="q">Search</label>
                    <input
                        type="search"
                        id="q"
                        name="q"
                        value="{{ $searchTerm }}"
                        class="admin-input"
                        placeholder="Title, owner, or city">
                </div>

                <fieldset class="admin-properties-filter-group admin-properties-radio-group">
                    <legend>Property Type</legend>
                    <div class="admin-properties-radio-list">
                        <label class="admin-properties-radio-option">
                            <input type="radio" name="property_type" value="" @checked(empty($selectedPropertyType))>
                            <span>All Types</span>
                        </label>

                        @foreach ($propertyTypeOptions as $value => $label)
                            <label class="admin-properties-radio-option">
                                <input type="radio" name="property_type" value="{{ $value }}" @checked($selectedPropertyType === $value)>
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>

                <fieldset class="admin-properties-filter-group admin-properties-radio-group">
                    <legend>Verification</legend>
                    <div class="admin-properties-radio-list">
                        <label class="admin-properties-radio-option">
                            <input type="radio" name="verification" value="" @checked(empty($selectedVerification))>
                            <span>All Status</span>
                        </label>

                        @foreach ($verificationOptions as $value => $label)
                            <label class="admin-properties-radio-option">
                                <input type="radio" name="verification" value="{{ $value }}" @checked($selectedVerification === $value)>
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>

                <div class="admin-properties-filter-group">
                    <label for="city">City</label>
                    <input
                        type="text"
                        id="city"
                        name="city"
                        value="{{ $selectedCity }}"
                        class="admin-input"
                        placeholder="Enter city">
                </div>

                <div class="admin-properties-filter-group">
                    <label for="min_rent">Rent Range</label>
                    <div class="admin-properties-range-row">
                        <input
                            type="number"
                            id="min_rent"
                            name="min_rent"
                            value="{{ $minRent !== null ? $minRent : '' }}"
                            class="admin-input"
                            min="0"
                            step="0.01"
                            placeholder="Min">
                        <span class="admin-properties-range-separator">to</span>
                        <input
                            type="number"
                            id="max_rent"
                            name="max_rent"
                            value="{{ $maxRent !== null ? $maxRent : '' }}"
                            class="admin-input"
                            min="0"
                            step="0.01"
                            placeholder="Max">
                    </div>
                </div>

                <div class="admin-filter-actions admin-properties-filter-actions">
                    <button type="submit" class="admin-btn admin-btn-primary">Apply</button>
                    <a href="{{ route('admin.properties.index', ['tab' => $activeTab]) }}" class="admin-btn admin-btn-secondary">Reset</a>
                </div>
            </form>
        </aside>

        <section class="content-card admin-properties-card admin-properties-results-card">
            <div class="content-card-header admin-properties-results-header">
                <div>
                    <h2>{{ $activeTab === 'requests' ? 'Listing Requests' : 'Current Listings' }}</h2>
                    <p>{{ $properties->total() }} {{ $properties->total() === 1 ? 'listing' : 'listings' }} in this view.</p>
                </div>
            </div>

            <div class="admin-properties-grid">
                @forelse ($properties as $property)
                    @php
                        $propertyImage = $property->getFirstImageUrl();
                        $rentLabel = $property->canRentRooms()
                            ? $property->getOwnerPriceLabel()
                            : 'Rs ' . number_format((float) $property->rent_price) . ' / month';
                    @endphp

                    <article class="admin-property-card">
                        <a
                            href="{{ route('admin.properties.show', ['property' => $property, 'tab' => $activeTab]) }}"
                            class="admin-property-card-link"
                            aria-label="Open {{ $property->title }} details">
                        </a>

                        <div class="admin-property-media">
                            <img src="{{ $propertyImage }}" alt="{{ $property->title }}">
                        </div>

                        <div class="admin-property-body">
                            <div class="admin-property-head">
                                <div class="admin-property-head-copy">
                                    <h3 class="admin-property-title">{{ $property->title }}</h3>
                                    <p class="admin-property-owner">
                                        Owner: {{ $property->owner->name ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>

                            <div class="admin-property-meta">
                                <div class="admin-property-meta-item">
                                    <span>City</span>
                                    <strong>{{ $property->city ?? 'N/A' }}</strong>
                                </div>
                                <div class="admin-property-meta-item">
                                    <span>Rent</span>
                                    <strong>{{ $rentLabel }}</strong>
                                </div>
                            </div>

                            <div class="admin-property-badges">
                                <span class="status-pill {{ $property->is_verified ? 'status-approved' : 'status-neutral' }}">
                                    {{ $property->is_verified ? 'Verified' : 'Unverified' }}
                                </span>
                            </div>

                            <div class="admin-property-actions">
                                <a href="{{ route('admin.properties.edit', $property) }}" class="admin-btn admin-btn-secondary">Edit</a>

                                <form method="POST" action="{{ route('admin.properties.destroy', $property) }}" onsubmit="return confirm('Remove this property? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-btn admin-btn-danger admin-btn-danger-soft">Remove</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="empty-cell admin-properties-empty">
                        No listings match these filters.
                    </div>
                @endforelse
            </div>

            @if ($properties->hasPages())
                <div class="admin-properties-pagination">
                    <p class="admin-properties-pagination-info">
                        Showing {{ $properties->firstItem() }} to {{ $properties->lastItem() }} of {{ $properties->total() }} listings
                    </p>

                    <div class="admin-properties-pagination-links" role="navigation" aria-label="Property pagination">
                        @if ($properties->onFirstPage())
                            <span class="admin-properties-pagination-link disabled">Previous</span>
                        @else
                            <a href="{{ $properties->previousPageUrl() }}" class="admin-properties-pagination-link">Previous</a>
                        @endif

                        @foreach ($properties->elements() as $element)
                            @if (is_string($element))
                                <span class="admin-properties-pagination-ellipsis">{{ $element }}</span>
                            @endif

                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $properties->currentPage())
                                        <span class="admin-properties-pagination-link active">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url }}" class="admin-properties-pagination-link">{{ $page }}</a>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        @if ($properties->hasMorePages())
                            <a href="{{ $properties->nextPageUrl() }}" class="admin-properties-pagination-link">Next</a>
                        @else
                            <span class="admin-properties-pagination-link disabled">Next</span>
                        @endif
                    </div>
                </div>
            @endif
        </section>
    </div>
</div>
@endsection
