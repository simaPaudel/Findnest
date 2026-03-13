@extends('owner.layout')

@section('title', 'My Listings')
@section('page-title', 'My Listings')

@section('content')
<div class="content-card">
    <div class="card-header">
        <h2 class="card-title">Property Listings</h2>
        <a href="{{ route('owner.listings.create') }}" class="btn-primary">
            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Property
        </a>
    </div>

    <div class="table-responsive">
        @if($properties->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Property</th>
                        <th>Location</th>
                        <th>Rent/Month</th>
                        <th>Status</th>
                        <th>Verified</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($properties as $property)
                        <tr>
                            <td>
                                <div class="property-info">
                                    @php
                                        // Handle both JSON strings (old data) and arrays (new data with model casting)
                                        $photos = $property->photos;
                                        if (is_string($photos)) {
                                            $photos = json_decode($photos, true) ?? [];
                                        }
                                        $photos = $photos ?? [];
                                        $firstPhoto = !empty($photos) && isset($photos[0]) ? $photos[0] : null;
                                    @endphp
                                    
                                    @if($firstPhoto)
                                        <img src="{{ asset('storage/' . $firstPhoto) }}" alt="{{ $property->title }}" class="property-thumb">
                                    @else
                                        <div class="property-thumb-placeholder">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="property-title">{{ $property->title }}</div>
                                        <div class="property-type">{{ ucfirst($property->room_type) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $property->city }}</td>
                            <td class="text-bold">@npr($property->rent_price)</td>
                            <td>
                                <span class="badge badge-{{ $property->status }}">
                                    {{ ucfirst($property->status) }}
                                </span>
                            </td>
                            <td>
                                @if($property->is_verified)
                                    <span class="badge badge-verified">Verified</span>
                                @else
                                    <span class="badge badge-draft">Pending</span>
                                @endif
                            </td>
                            <td>{{ $property->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('owner.listings.edit', $property->id) }}" class="btn-icon-sm" title="Edit">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>

                                    <form method="POST" action="{{ route('owner.listings.toggle', $property->id) }}" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-icon-sm" title="{{ $property->status === 'approved' ? 'Set to Pending' : 'Approve' }}">
                                            @if($property->status === 'published')
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                                </svg>
                                            @else
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            @endif
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('owner.listings.destroy', $property->id) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this property?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon-sm btn-danger" title="Delete">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination-wrapper">
                {{ $properties->links() }}
            </div>
        @else
            <div class="empty-state">
                <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h3>No Properties Yet</h3>
                <p>Start by adding your first property listing.</p>
                <a href="{{ route('owner.listings.create') }}" class="btn-primary">Add New Property</a>
            </div>
        @endif
    </div>
</div>
@endsection
