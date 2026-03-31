@extends('admin.layout')

@section('title', 'Users')
@section('page_title', 'User Management')

@section('content')
    <div class="admin-dashboard">
        <section class="content-card">
            <div class="content-card-header">
                <div>
                    <h2>Filter Users</h2>
                    <p>Review platform accounts by role.</p>
                </div>
            </div>

            <div style="padding: 20px 22px;">
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

        <section class="content-card">
            <div class="content-card-header">
                <div>
                    <h2>All Users</h2>
                    <p>{{ $users->total() }} account{{ $users->total() === 1 ? '' : 's' }} found.</p>
                </div>
            </div>

            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Verified</th>
                            <th>Trust Points</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>
                                    <div class="admin-user-cell">
                                        @if ($user->profile_photo)
                                            <img
                                                src="{{ asset('storage/' . $user->profile_photo) }}"
                                                alt="{{ $user->name }}"
                                                class="admin-user-avatar"
                                            >
                                        @else
                                            <div class="admin-user-avatar admin-user-avatar-fallback">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif

                                        <div>
                                            <div class="primary-text">{{ $user->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?: 'N/A' }}</td>
                                <td>
                                    <span class="status-pill status-neutral">{{ $user->role }}</span>
                                </td>
                                <td>
                                    <span class="status-pill {{ $user->is_verified ? 'status-approved' : 'status-neutral' }}">
                                        {{ $user->is_verified ? 'Verified' : 'Unverified' }}
                                    </span>
                                </td>
                                <td>{{ $user->trust_points }}</td>
                                <td>{{ optional($user->created_at)->format('M d, Y') ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-cell">No users matched the current filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div style="padding: 18px 22px; border-top: 1px solid var(--fn-line);">
                    {{ $users->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection
