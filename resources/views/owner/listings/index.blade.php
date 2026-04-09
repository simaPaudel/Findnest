@extends('owner.layout')

@section('title', 'My Listings')
@section('page-title', 'My Listings')

@section('content')
<style>
    .owner-listings-card {
        overflow: visible;
    }

    .listing-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1.25rem;
        padding: 1.25rem;
        align-items: start;
    }

    .listing-card {
        background: #fff;
        border: 1px solid #e7edf3;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        display: flex;
        flex-direction: column;
        min-height: 100%;
        transition: border-color 0.18s ease, box-shadow 0.18s ease, transform 0.18s ease;
    }

    .listing-card:hover {
        border-color: #d8e1ea;
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
        transform: translateY(-1px);
    }

    .listing-card-media {
        position: relative;
        height: 210px;
        background: #f8fafc;
    }

    .listing-card-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .listing-card-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        font-size: 0.88rem;
        font-weight: 500;
    }

    .listing-card-badge-wrap {
        position: absolute;
        top: 0.9rem;
        left: 0.9rem;
    }

    .listing-mode-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.7rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.02em;
        background: rgba(15, 23, 42, 0.74);
        color: #fff;
    }

    .listing-card-body {
        padding: 1rem 1rem 1.05rem;
        display: flex;
        flex-direction: column;
        gap: 0.9rem;
        flex: 1;
    }

    .listing-card-title {
        font-size: 1.02rem;
        font-weight: 600;
        color: #0f172a;
        line-height: 1.45;
        letter-spacing: -0.01em;
    }

    .listing-card-subtitle {
        margin-top: 0.28rem;
        color: #64748b;
        font-size: 0.84rem;
        line-height: 1.5;
    }

    .listing-meta-row {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        flex-wrap: wrap;
    }

    .listing-status-badge,
    .listing-verified-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.28rem 0.6rem;
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.01em;
    }

    .listing-status-badge.pending {
        background: #fff7ed;
        color: #b45309;
    }

    .listing-status-badge.approved {
        background: #ecfdf5;
        color: #047857;
    }

    .listing-status-badge.rejected {
        background: #fff1f2;
        color: #b91c1c;
    }

    .listing-verified-badge.verified {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .listing-verified-badge.pending {
        background: #f8fafc;
        color: #475569;
    }

    .listing-meta {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.9rem 1rem;
        padding-top: 0.2rem;
        border-top: 1px solid #eef2f7;
    }

    .listing-meta-label {
        display: block;
        font-size: 0.69rem;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 0.2rem;
    }

    .listing-meta-value {
        color: #0f172a;
        font-size: 0.88rem;
        font-weight: 500;
        line-height: 1.4;
    }

    .listing-actions {
        display: flex;
        gap: 0.55rem;
        flex-wrap: wrap;
        margin-top: auto;
        padding-top: 0.2rem;
    }

    .listing-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.62rem 0.9rem;
        border-radius: 10px;
        border: 1px solid transparent;
        font-weight: 600;
        font-size: 0.84rem;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.18s ease, border-color 0.18s ease, color 0.18s ease, transform 0.18s ease;
        flex: 1;
        min-width: 88px;
    }

    .listing-action-btn:hover {
        transform: translateY(-1px);
    }

    .listing-action-btn.primary {
        background: #fff3f5;
        border-color: #ffd4dc;
        color: #be123c;
    }

    .listing-action-btn.secondary {
        background: #fff;
        border-color: #e5e7eb;
        color: #475569;
    }

    .listing-action-btn.danger {
        background: #fff1f2;
        border-color: #ffe4e6;
        color: #dc2626;
    }

    .listing-actions form {
        flex: 1;
    }

    .empty-state-card {
        text-align: center;
        padding: 3rem 2rem;
        border-top: 1px solid #eef2f7;
    }

    .owner-listings-empty-title {
        font-size: 1rem;
        font-weight: 600;
        color: #0f172a;
    }

    .owner-listings-empty-text {
        margin-top: 0.5rem;
        color: #64748b;
        font-size: 0.9rem;
    }

    .owner-listings-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.05rem 1.25rem;
        border-bottom: 1px solid #eef2f7;
    }

    .owner-listings-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.62rem 0.9rem;
        border-radius: 10px;
        background: #ff385c;
        color: #fff;
        text-decoration: none;
        font-size: 0.84rem;
        font-weight: 600;
        transition: background 0.18s ease, transform 0.18s ease;
    }

    .owner-listings-button:hover {
        background: #e11d48;
        transform: translateY(-1px);
    }

    .owner-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .owner-pagination-info {
        font-size: 0.82rem;
        color: #64748b;
    }

    .owner-pagination-links {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .owner-pagination-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 0.8rem;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        background: #fff;
        color: #475569;
        font-size: 0.82rem;
        font-weight: 500;
        text-decoration: none;
        transition: background 0.18s ease, border-color 0.18s ease, color 0.18s ease;
    }

    .owner-pagination-link:hover {
        background: #f8fafc;
        border-color: #dbe4ee;
        color: #0f172a;
    }

    .owner-pagination-link.active {
        background: #fff3f5;
        border-color: #ffd4dc;
        color: #be123c;
    }

    .owner-pagination-link.disabled {
        background: #f8fafc;
        color: #94a3b8;
        pointer-events: none;
    }

    @media (max-width: 1200px) {
        .listing-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 992px) {
        .listing-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .listing-grid {
            grid-template-columns: 1fr;
            padding: 1rem;
        }

        .owner-listings-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .listing-card-media {
            height: 195px;
        }

        .listing-meta {
            grid-template-columns: 1fr;
        }

        .listing-actions {
            flex-direction: column;
        }

        .listing-actions form,
        .listing-action-btn {
            width: 100%;
        }
    }
</style>

<div class="content-card owner-listings-card">
    <div class="owner-listings-header">
        <h2 class="card-title">Property Listings</h2>
        <a href="{{ route('owner.listings.create') }}" class="owner-listings-button">
            Add New Property
        </a>
    </div>

    @if($properties->count() > 0)
    <div class="listing-grid">
        @foreach($properties as $property)
        @php($listingImage = $property->getFirstImageUrl(false))
        <article class="listing-card">
            <div class="listing-card-media">
                @if($listingImage)
                <img src="{{ $listingImage }}" alt="{{ $property->title }}">
                @else
                <div class="listing-card-placeholder">No image yet</div>
                @endif

                <div class="listing-card-badge-wrap">
                    <span class="listing-mode-badge">{{ $property->getRentalModeLabel() }}</span>
                </div>
            </div>

            <div class="listing-card-body">
                <div>
                    <h3 class="listing-card-title">{{ $property->title }}</h3>
                    <p class="listing-card-subtitle">{{ $property->getOwnerListingSummary() }}</p>
                    <div class="listing-meta-row" style="margin-top: 0.7rem;">
                        <span class="listing-status-badge {{ $property->status }}">{{ ucfirst($property->status) }}</span>
                        <span class="listing-verified-badge {{ $property->is_verified ? 'verified' : 'pending' }}">{{ $property->is_verified ? 'Verified' : 'Pending Review' }}</span>
                    </div>
                </div>

                <div class="listing-meta">
                    <div>
                        <span class="listing-meta-label">Address</span>
                        <span class="listing-meta-value">{{ $property->address }}</span>
                    </div>
                    <div>
                        <span class="listing-meta-label">City</span>
                        <span class="listing-meta-value">{{ $property->city }}</span>
                    </div>
                    <div>
                        <span class="listing-meta-label">Price</span>
                        <span class="listing-meta-value">{{ $property->getOwnerPriceLabel() }}</span>
                    </div>
                    <div>
                        <span class="listing-meta-label">Created</span>
                        <span class="listing-meta-value">{{ $property->created_at->format('M d, Y') }}</span>
                    </div>
                </div>

                <div class="listing-actions">
                    <a href="{{ route('owner.listings.edit', $property) }}" class="listing-action-btn primary">
                        Edit
                    </a>

                    <a href="{{ $property->status === 'approved' ? route('listings.show', $property) : route('owner.listings.edit', $property) }}" class="listing-action-btn secondary">
                        View
                    </a>

                    <form method="POST" action="{{ route('owner.listings.destroy', $property) }}" onsubmit="return confirm('Are you sure you want to delete this property?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="listing-action-btn danger">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </article>
        @endforeach
    </div>

    <div class="pagination-wrapper" style="margin-top: 0;">
        <div class="owner-pagination">
            <p class="owner-pagination-info">
                Showing {{ $properties->firstItem() }} to {{ $properties->lastItem() }} of {{ $properties->total() }} results
            </p>

            @if($properties->hasPages())
            <div class="owner-pagination-links">
                @if($properties->onFirstPage())
                    <span class="owner-pagination-link disabled">Previous</span>
                @else
                    <a href="{{ $properties->previousPageUrl() }}" class="owner-pagination-link">Previous</a>
                @endif

                @foreach($properties->getUrlRange(1, $properties->lastPage()) as $page => $url)
                    @if($page == $properties->currentPage())
                        <span class="owner-pagination-link active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="owner-pagination-link">{{ $page }}</a>
                    @endif
                @endforeach

                @if($properties->hasMorePages())
                    <a href="{{ $properties->nextPageUrl() }}" class="owner-pagination-link">Next</a>
                @else
                    <span class="owner-pagination-link disabled">Next</span>
                @endif
            </div>
            @endif
        </div>
    </div>
    @else
    <div class="empty-state-card">
        <h3 class="owner-listings-empty-title">No Properties Yet</h3>
        <p class="owner-listings-empty-text">Start by adding your first property listing.</p>
        <a href="{{ route('owner.listings.create') }}" class="owner-listings-button" style="margin-top: 1rem;">Add New Property</a>
    </div>
    @endif
</div>
@endsection
