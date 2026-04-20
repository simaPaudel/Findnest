@extends('admin.layout')

@section('title', 'Properties')
@section('page_title', 'Properties')
@section('hide_pagebar', 'true')

@section('content')
<div class="admin-dashboard admin-properties-page">
    <section class="content-card admin-properties-filters-card">
        <div class="content-card-header">
            <div>
                <h2>Filters</h2>
                <p>Narrow listings by status or city.</p>
            </div>
        </div>

        <div class="admin-properties-card-body">
            <form method="GET" action="{{ route('admin.properties.index') }}" class="admin-filters admin-properties-filter-form">
                <div class="admin-filter-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="admin-input">
                        <option value="">All Statuses</option>
                        <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                        <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                        <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                    </select>
                </div>

                <div class="admin-filter-group">
                    <label for="city">City</label>
                    <input
                        type="text"
                        id="city"
                        name="city"
                        value="{{ request('city') }}"
                        class="admin-input"
                        placeholder="Enter city">
                </div>

                <div class="admin-filter-actions">
                    <button type="submit" class="admin-btn admin-btn-primary">Apply</button>
                    <a href="{{ route('admin.properties.index') }}" class="admin-btn admin-btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </section>

    <section class="content-card admin-properties-card admin-properties-results-card">
        <div class="content-card-header admin-properties-results-header">
            <div>
                <h2>Listings</h2>
                <p>{{ $properties->total() }} total.</p>
            </div>
        </div>

        <div class="admin-properties-grid">
            @forelse ($properties as $property)
                @php
                    $propertyImage = $property->getFirstImageUrl();
                    $roomCount = $property->rooms->count();
                    $summary = $property->getOwnerListingSummary();
                @endphp

                <article class="admin-property-card">
                    <a href="{{ route('listings.show', $property) }}" class="admin-property-media">
                        <img src="{{ $propertyImage }}" alt="{{ $property->title }}">
                        <div class="admin-property-media-overlay">
                            <span class="admin-property-city">{{ $property->city ?? 'N/A' }}</span>
                            <span class="admin-property-view-link">View details</span>
                        </div>
                    </a>

                    <div class="admin-property-body">
                        <div class="admin-property-head">
                            <div class="admin-property-head-copy">
                                <h3 class="admin-property-title">{{ $property->title }}</h3>
                                <p class="admin-property-owner">
                                    {{ $property->owner->name ?? 'N/A' }}
                                </p>
                            </div>

                            <a href="{{ route('listings.show', $property) }}" class="admin-property-open-link">
                                Open
                            </a>
                        </div>

                        <div class="admin-property-meta">
                            <div class="admin-property-meta-item">
                                <span>City</span>
                                <strong>{{ $property->city ?? 'N/A' }}</strong>
                            </div>
                            <div class="admin-property-meta-item">
                                <span>Rent</span>
                                <strong>Rs {{ number_format((float) $property->rent_price, 2) }}</strong>
                            </div>
                            <div class="admin-property-meta-item">
                                <span>Type</span>
                                <strong>{{ $property->getPropertyTypeLabel() ?? 'N/A' }}</strong>
                            </div>
                            <div class="admin-property-meta-item">
                                <span>Rooms</span>
                                <strong>{{ $roomCount > 0 ? $roomCount : '0' }}</strong>
                            </div>
                        </div>

                        <div class="admin-property-badges">
                            <span class="status-pill status-{{ $property->status }}">{{ $property->status }}</span>
                            <span class="status-pill {{ $property->is_verified ? 'status-approved' : 'status-neutral' }}">
                                {{ $property->is_verified ? 'Verified' : 'Unverified' }}
                            </span>
                        </div>

                        <div class="admin-property-summary">
                            <span>{{ $summary }}</span>
                            <span>Created {{ optional($property->created_at)->format('M d, Y') ?? 'N/A' }}</span>
                        </div>

                        <div class="admin-property-actions">
                            @if ($property->status !== 'approved')
                                <form method="POST" action="{{ route('admin.properties.approve', $property) }}">
                                    @csrf
                                    <button type="submit" class="admin-btn admin-btn-success">Approve</button>
                                </form>
                            @endif

                            @if ($property->status !== 'rejected')
                                <form method="POST" action="{{ route('admin.properties.reject', $property) }}">
                                    @csrf
                                    <button type="submit" class="admin-btn admin-btn-danger">Reject</button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('admin.properties.verify', $property) }}">
                                @csrf
                                <button type="submit" class="admin-btn admin-btn-secondary">
                                    {{ $property->is_verified ? 'Unverify' : 'Verify' }}
                                </button>
                            </form>

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
                {{ $properties->links() }}
            </div>
        @endif
    </section>
</div>
@endsection
