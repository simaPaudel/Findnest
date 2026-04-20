@extends('admin.layout')

@section('title', 'Reviews')
@section('page_title', 'Review Moderation')
@section('hide_pagebar', 'true')

@section('content')
    <div class="admin-dashboard">
        <section class="content-card">
            <div class="content-card-header">
                <div>
                    <h2>All Reviews</h2>
                    <p>{{ $reviews->total() }} review{{ $reviews->total() === 1 ? '' : 's' }} available for moderation.</p>
                </div>
            </div>

            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Reviewer</th>
                            <th>Property</th>
                            <th>Rating</th>
                            <th>Review</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reviews as $review)
                            <tr>
                                <td>{{ $review->user->name ?? 'N/A' }}</td>
                                <td>{{ $review->property->title ?? 'N/A' }}</td>
                                <td>{{ $review->rating }}/5</td>
                                <td>
                                    <div class="primary-text">{{ \Illuminate\Support\Str::limit($review->review_text, 110) }}</div>
                                </td>
                                <td>
                                    <span class="status-pill {{ $review->is_approved ? 'status-approved' : 'status-neutral' }}">
                                        {{ $review->is_approved ? 'Approved' : 'Pending Review' }}
                                    </span>
                                </td>
                                <td>{{ optional($review->created_at)->format('M d, Y') ?? 'N/A' }}</td>
                                <td>
                                    <div class="admin-action-stack">
                                        @if (! $review->is_approved)
                                            <form method="POST" action="{{ route('admin.reviews.approve', $review) }}">
                                                @csrf
                                                <button type="submit" class="admin-btn admin-btn-success">Approve</button>
                                            </form>
                                        @endif

                                        @if ($review->is_approved)
                                            <form method="POST" action="{{ route('admin.reviews.hide', $review) }}">
                                                @csrf
                                                <button type="submit" class="admin-btn admin-btn-danger">Hide</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-cell">No reviews found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($reviews->hasPages())
                <div style="padding: 18px 22px; border-top: 1px solid var(--fn-line);">
                    {{ $reviews->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection
