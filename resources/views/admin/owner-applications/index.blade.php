@extends('admin.layout')

@section('title', 'Applications')
@section('page_title', 'Applications')
@section('hide_pagebar', 'true')

@section('content')
    <div class="admin-applications-page admin-users-shell">
        <aside class="content-card admin-users-filter-panel admin-applications-filters-card">
            <div class="admin-users-filter-heading">
                <span class="admin-section-label">Filters</span>
                <h2>Host applications</h2>
                <p>Search by applicant name and filter by role.</p>
            </div>

            <form method="GET" action="{{ route('admin.owner-applications.index') }}" class="admin-users-filter-form">
                <div class="admin-filter-group">
                    <label for="q">Search name</label>
                    <input
                        id="q"
                        type="search"
                        name="q"
                        value="{{ $searchTerm }}"
                        class="admin-input"
                        placeholder="Applicant name"
                    >
                </div>

                <div class="admin-users-filter-section">
                    <span class="admin-users-filter-label">Role</span>

                    <label class="admin-users-radio-option">
                        <input type="radio" name="role" value="" @checked(empty($selectedRole))>
                        <span></span>
                        <strong>All roles</strong>
                    </label>

                    @foreach ($roleOptions as $value => $label)
                        <label class="admin-users-radio-option">
                            <input type="radio" name="role" value="{{ $value }}" @checked($selectedRole === $value)>
                            <span></span>
                            <strong>{{ $label }}</strong>
                        </label>
                    @endforeach
                </div>

                <div class="admin-users-filter-actions">
                    <button type="submit" class="admin-btn admin-btn-primary">Apply filters</button>
                    <a href="{{ route('admin.owner-applications.index') }}" class="admin-btn admin-btn-secondary">Reset</a>
                </div>
            </form>
        </aside>

        <section class="content-card admin-applications-results-card">
            <div class="content-card-header admin-panel-header">
                <div>
                    <h2>Applications</h2>
                    <p>{{ $applications->total() }} application{{ $applications->total() === 1 ? '' : 's' }} found.</p>
                </div>

                <span class="admin-card-chip">View</span>
            </div>

            <div class="admin-applications-grid">
                @forelse ($applications as $application)
                    @php
                        $applicantAvatarUrl = $application->user && method_exists($application->user, 'profilePhotoUrl')
                            ? $application->user->profilePhotoUrl()
                            : null;
                        $applicantAvatarInitial = $application->user && method_exists($application->user, 'avatarInitial')
                            ? $application->user->avatarInitial()
                            : strtoupper(substr($application->full_name ?? 'U', 0, 1));

                        $statusClass = match ($application->status) {
                            'approved' => 'status-approved',
                            'rejected' => 'status-rejected',
                            default => 'status-pending',
                        };
                    @endphp

                    <article class="admin-application-card">
                        <div class="admin-application-card-top">
                            <div class="admin-application-avatar">
                                @if ($applicantAvatarUrl)
                                    <img
                                        src="{{ $applicantAvatarUrl }}"
                                        alt="{{ $application->full_name }}"
                                        onerror="this.style.display='none'; this.nextElementSibling.removeAttribute('hidden');"
                                    >
                                    <span class="admin-application-avatar-fallback" hidden>{{ $applicantAvatarInitial }}</span>
                                @else
                                    <span class="admin-application-avatar-fallback">{{ $applicantAvatarInitial }}</span>
                                @endif
                            </div>

                            <div class="admin-application-copy">
                                <h3>{{ $application->full_name }}</h3>
                                <p>{{ optional($application->user)->email ?? 'N/A' }}</p>
                                <span>{{ $application->phone }}</span>
                            </div>

                            <span class="status-pill {{ $statusClass }}">{{ ucfirst($application->status) }}</span>
                        </div>

                        <div class="admin-application-meta-grid">
                            <div class="admin-application-meta">
                                <span>Submitted</span>
                                <strong>{{ optional($application->submitted_at)->format('M d, Y') ?? 'N/A' }}</strong>
                            </div>

                            <div class="admin-application-meta">
                                <span>Citizenship</span>
                                <strong>{{ $application->citizenship_number }}</strong>
                            </div>
                        </div>

                        <div class="admin-application-card-actions">
                            <a href="{{ route('admin.owner-applications.show', $application) }}" class="admin-btn admin-btn-secondary">
                                View
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="admin-applications-empty">No host applications matched the current filter.</div>
                @endforelse
            </div>

            @if ($applications->hasPages())
                <div class="admin-applications-pagination">
                    {{ $applications->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection
