@extends('admin.layout')

@section('title', 'Host Applications')
@section('page_title', 'Host Applications')

@section('content')
    <div class="admin-dashboard">
        <section class="content-card">
            <div class="content-card-header">
                <div>
                    <h2>Filter Applications</h2>
                    <p>Review host requests by their current status.</p>
                </div>
            </div>

            <div style="padding: 20px 22px;">
                <form method="GET" action="{{ route('admin.owner-applications.index') }}" class="admin-filters">
                    <div class="admin-filter-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="admin-input">
                            <option value="">All Statuses</option>
                            <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                            <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                            <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                        </select>
                    </div>

                    <div class="admin-filter-actions">
                        <button type="submit" class="admin-btn admin-btn-primary">Apply Filter</button>
                        <a href="{{ route('admin.owner-applications.index') }}" class="admin-btn admin-btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </section>

        <section class="content-card">
            <div class="content-card-header">
                <div>
                    <h2>All Host Applications</h2>
                    <p>{{ $applications->total() }} application{{ $applications->total() === 1 ? '' : 's' }} found.</p>
                </div>
            </div>

            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Applicant</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Reviewed</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($applications as $application)
                            <tr>
                                <td>
                                    <div class="primary-text">{{ $application->full_name }}</div>
                                    <div class="muted-text">{{ $application->user->email ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <div class="primary-text">{{ $application->phone }}</div>
                                    <div class="muted-text">Citizenship #: {{ $application->citizenship_number }}</div>
                                </td>
                                <td>
                                    @php
                                        $statusClass = match ($application->status) {
                                            'approved' => 'status-approved',
                                            'rejected' => 'status-rejected',
                                            default => 'status-pending',
                                        };
                                    @endphp
                                    <span class="status-pill {{ $statusClass }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="primary-text">{{ optional($application->submitted_at)->format('M d, Y') ?? 'N/A' }}</div>
                                    <div class="muted-text">{{ optional($application->submitted_at)->format('h:i A') ?? '' }}</div>
                                </td>
                                <td>
                                    <div class="primary-text">{{ optional($application->reviewed_at)->format('M d, Y') ?? 'Waiting review' }}</div>
                                    <div class="muted-text">{{ optional($application->reviewed_at)->format('h:i A') ?? '' }}</div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.owner-applications.show', $application) }}" class="admin-inline-link">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-cell">No host applications matched the current filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($applications->hasPages())
                <div style="padding: 18px 22px; border-top: 1px solid var(--fn-line);">
                    {{ $applications->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection
