@extends('admin.layout')

@section('title', 'Properties')
@section('page_title', 'Property Moderation')

@section('content')
<div class="admin-dashboard">
    <section class="content-card">
        <div class="content-card-header">
            <div>
                <h2>Filter Properties</h2>
                <p>Review listings by status and city before moderation.</p>
            </div>
        </div>

        <div style="padding: 20px 22px;">
            <form method="GET" action="{{ route('admin.properties.index') }}" class="admin-filters">
                <div class="admin-filter-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="admin-input">
                        <option value="">All Statuses</option>
                        <option value="pending" @selected(request('status')==='pending' )>Pending</option>
                        <option value="approved" @selected(request('status')==='approved' )>Approved</option>
                        <option value="rejected" @selected(request('status')==='rejected' )>Rejected</option>
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
                    <button type="submit" class="admin-btn admin-btn-primary">Apply Filters</button>
                    <a href="{{ route('admin.properties.index') }}" class="admin-btn admin-btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </section>

    <section class="content-card">
        <div class="content-card-header">
            <div>
                <h2>All Properties</h2>
                <p>{{ $properties->total() }} listing{{ $properties->total() === 1 ? '' : 's' }} found.</p>
            </div>
        </div>

        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Owner</th>
                        <th>City</th>
                        <th>Rent</th>
                        <th>Room Type</th>
                        <th>Status</th>
                        <th>Verified</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($properties as $property)
                    <tr>
                        <td>
                            <div class="primary-text">{{ $property->title }}</div>
                            @if ($property->status === 'approved')
                            <div class="muted-text">
                                <a href="{{ route('listings.show', $property) }}" class="admin-inline-link" target="_blank" rel="noopener">
                                    View public listing
                                </a>
                            </div>
                            @endif
                        </td>
                        <td>{{ $property->owner->name ?? 'N/A' }}</td>
                        <td>{{ $property->city ?? 'N/A' }}</td>
                        <td>Rs {{ number_format((float) $property->rent_price, 2) }}</td>
                        <td>{{ $property->getPropertyTypeLabel() ?? 'N/A' }}</td>
                        <td>
                            <span class="status-pill status-{{ $property->status }}">
                                {{ $property->status }}
                            </span>
                        </td>
                        <td>
                            <span class="status-pill {{ $property->is_verified ? 'status-approved' : 'status-neutral' }}">
                                {{ $property->is_verified ? 'Verified' : 'Unverified' }}
                            </span>
                        </td>
                        <td>{{ optional($property->created_at)->format('M d, Y') ?? 'N/A' }}</td>
                        <td>
                            <div class="admin-action-stack">
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
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="empty-cell">No properties matched your filters.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($properties->hasPages())
        <div style="padding: 18px 22px; border-top: 1px solid var(--fn-line);">
            {{ $properties->links() }}
        </div>
        @endif
    </section>
</div>
@endsection