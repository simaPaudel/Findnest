@extends('admin.layout')

@section('title', 'Users')
@section('page_title', 'Users')
@section('hide_pagebar', 'true')

@section('content')
    <div class="admin-dashboard admin-users-page">
        <div class="admin-users-shell">
            <aside class="content-card admin-users-filter-panel">
                <div class="admin-users-filter-heading">
                    <span class="admin-section-label">Filters</span>
                    <h2>User management</h2>
                </div>

                <form method="GET" action="{{ route('admin.users.index') }}" class="admin-users-filter-form">
                    <div class="admin-filter-group">
                        <label for="q">Search user</label>
                        <input
                            id="q"
                            type="search"
                            name="q"
                            value="{{ request('q') }}"
                            class="admin-input"
                            placeholder="Name or email"
                        >
                    </div>

                    <div class="admin-users-filter-section">
                        <span class="admin-users-filter-label">Role</span>
                        <label class="admin-users-radio-option">
                            <input type="radio" name="role" value="" @checked(! request()->filled('role'))>
                            <span></span>
                            <strong>All roles</strong>
                        </label>
                        <label class="admin-users-radio-option">
                            <input type="radio" name="role" value="user" @checked(request('role') === 'user')>
                            <span></span>
                            <strong>User</strong>
                        </label>
                        <label class="admin-users-radio-option">
                            <input type="radio" name="role" value="owner" @checked(request('role') === 'owner')>
                            <span></span>
                            <strong>Owner</strong>
                        </label>
                        <label class="admin-users-radio-option">
                            <input type="radio" name="role" value="admin" @checked(request('role') === 'admin')>
                            <span></span>
                            <strong>Admin</strong>
                        </label>
                    </div>

                    <div class="admin-users-filter-section">
                        <span class="admin-users-filter-label">Status</span>
                        <label class="admin-users-radio-option">
                            <input type="radio" name="status" value="" @checked(! request()->filled('status'))>
                            <span></span>
                            <strong>All statuses</strong>
                        </label>
                        <label class="admin-users-radio-option">
                            <input type="radio" name="status" value="active" @checked(request('status') === 'active')>
                            <span></span>
                            <strong>Active only</strong>
                        </label>
                        <label class="admin-users-radio-option">
                            <input type="radio" name="status" value="blocked" @checked(request('status') === 'blocked')>
                            <span></span>
                            <strong>Blocked only</strong>
                        </label>
                    </div>

                    <div class="admin-users-filter-section">
                        <span class="admin-users-filter-label">Verification</span>
                        <label class="admin-users-radio-option">
                            <input type="radio" name="verification" value="" @checked(! request()->filled('verification'))>
                            <span></span>
                            <strong>All verification</strong>
                        </label>
                        <label class="admin-users-radio-option">
                            <input type="radio" name="verification" value="verified" @checked(request('verification') === 'verified')>
                            <span></span>
                            <strong>Verified</strong>
                        </label>
                        <label class="admin-users-radio-option">
                            <input type="radio" name="verification" value="unverified" @checked(request('verification') === 'unverified')>
                            <span></span>
                            <strong>Unverified</strong>
                        </label>
                    </div>

                    <div class="admin-users-filter-actions">
                        <button type="submit" class="admin-btn admin-btn-primary">Apply filters</button>
                        <a href="{{ route('admin.users.index') }}" class="admin-btn admin-btn-secondary">Reset</a>
                    </div>
                </form>
            </aside>

            <section class="content-card admin-users-results-card">
                <div class="content-card-header admin-panel-header">
                    <div>
                        <h2>Users</h2>
                        <p>{{ $users->total() }} account{{ $users->total() === 1 ? '' : 's' }} found.</p>
                    </div>

                    <span class="admin-card-chip">Safe account actions</span>
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
                                    <a href="{{ route('admin.users.edit', $user) }}" class="admin-btn admin-btn-secondary">
                                        Edit User
                                    </a>

                                    @if (! $user->isAdmin())
                                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="admin-btn {{ $user->is_blocked ? 'admin-btn-success' : 'admin-btn-danger' }}"
                                            >
                                                {{ $user->is_blocked ? 'Unblock user' : 'Block user' }}
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
    </div>
@endsection
