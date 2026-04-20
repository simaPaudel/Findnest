@extends('admin.layout')

@section('title', 'Users')
@section('page_title', 'Users')
@section('hide_pagebar', 'true')

@section('content')
    <div class="admin-dashboard admin-users-page">
        <section class="content-card">
            <div class="content-card-header admin-panel-header">
                <div>
                    <h2>Filters</h2>
                    <p>Browse every account, open profile details, and manage access.</p>
                </div>

                <span class="admin-card-chip">View all users</span>
            </div>

            <div class="admin-users-filter-body">
                <form method="GET" action="{{ route('admin.users.index') }}" class="admin-filters">
                    <div class="admin-filter-group">
                        <label for="role">Role</label>
                        <select id="role" name="role" class="admin-input">
                            <option value="">All Roles</option>
                            <option value="user" @selected(request('role') === 'user')>User</option>
                            <option value="owner" @selected(request('role') === 'owner')>Owner</option>
                            <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                        </select>
                    </div>

                    <div class="admin-filter-actions">
                        <button type="submit" class="admin-btn admin-btn-primary">Apply Filter</button>
                        <a href="{{ route('admin.users.index') }}" class="admin-btn admin-btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </section>

        <section class="content-card admin-users-results-card">
            <div class="content-card-header admin-panel-header">
                <div>
                    <h2>All Users</h2>
                    <p>{{ $users->total() }} account{{ $users->total() === 1 ? '' : 's' }} found.</p>
                </div>

                <span class="admin-card-chip">View user details</span>
            </div>

            <div class="admin-users-grid">
                @forelse ($users as $user)
                    @php($userAvatarUrl = $user->profilePhotoUrl())

                    <article class="admin-user-card">
                        <div class="admin-user-card-top">
                            <div class="admin-user-card-avatar">
                                @if ($userAvatarUrl)
                                    <img
                                        src="{{ $userAvatarUrl }}"
                                        alt="{{ $user->name }}"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                    >
                                    <span class="admin-user-card-avatar-fallback" style="display:none;">{{ $user->avatarInitial() }}</span>
                                @else
                                    <span class="admin-user-card-avatar-fallback">{{ $user->avatarInitial() }}</span>
                                @endif
                            </div>

                            <div class="admin-user-card-copy">
                                <h3>{{ $user->name }}</h3>
                                <p>{{ $user->email }}</p>
                                <span>{{ $user->phone ?: 'No phone number' }}</span>
                            </div>
                        </div>

                        <div class="admin-user-card-badges">
                            <span class="status-pill status-neutral">{{ ucfirst($user->role) }}</span>
                            <span class="status-pill {{ $user->is_verified ? 'status-approved' : 'status-neutral' }}">
                                {{ $user->is_verified ? 'Verified' : 'Unverified' }}
                            </span>
                            <span class="status-pill {{ $user->is_blocked ? 'status-rejected' : 'status-approved' }}">
                                {{ $user->is_blocked ? 'Blocked' : 'Active' }}
                            </span>
                        </div>

                        <div class="admin-user-card-body">
                            <div class="admin-user-summary-grid admin-user-summary-grid-index">
                                <div class="admin-user-mini-stat">
                                    <span>Bookings</span>
                                    <strong>{{ $user->bookings_count }}</strong>
                                </div>

                                <div class="admin-user-mini-stat">
                                    <span>Joined</span>
                                    <strong>{{ optional($user->created_at)->format('M d, Y') ?? 'N/A' }}</strong>
                                </div>
                            </div>

                            <div class="admin-user-card-actions">
                                <a href="{{ route('admin.users.show', $user) }}" class="admin-btn admin-btn-secondary">
                                    View user details
                                </a>

                                @if (! $user->isAdmin())
                                    <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="admin-btn {{ $user->is_blocked ? 'admin-btn-success' : 'admin-btn-danger' }}"
                                        >
                                            {{ $user->is_blocked ? 'Reactivate user' : 'Block user' }}
                                        </button>
                                    </form>
                                @else
                                    <span class="admin-meta-note">Admin accounts are protected.</span>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="admin-users-empty">No users matched the current filter.</div>
                @endforelse
            </div>

            @if ($users->hasPages())
                <div class="admin-properties-pagination">
                    {{ $users->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection
