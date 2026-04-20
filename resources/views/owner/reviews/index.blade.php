@extends('owner.layout')

@section('title', 'Reviews')
@section('page-title', 'Customer Reviews')

@section('content')
<div class="owner-reviews-page">
    <section class="reviews-hero">
        <div class="reviews-hero-copy">
            <p class="reviews-kicker">Overview</p>
            <h2 class="reviews-title">Reviews</h2>
            <p class="reviews-subtitle">Monitor what guests are saying about your spaces and stay quality.</p>
        </div>

        <div class="reviews-summary-grid" aria-label="Review summary">
            <div class="reviews-summary-card">
                <span class="reviews-summary-label">Average Rating</span>
                <strong class="reviews-summary-value">{{ number_format($avgRating, 1) }}/5</strong>
            </div>
            <div class="reviews-summary-card">
                <span class="reviews-summary-label">Total Reviews</span>
                <strong class="reviews-summary-value">{{ $reviews->total() }}</strong>
            </div>
        </div>
    </section>

    <div class="content-card reviews-shell">
        <div class="card-header reviews-card-header">
            <div>
                <h2 class="card-title">Latest Guest Feedback</h2>
                <p class="card-subtitle">Recent reviews from verified stays and completed bookings.</p>
            </div>
        </div>

        <div class="reviews-container">
            @if($reviews->count() > 0)
                @foreach($reviews as $review)
                    <article class="review-card">
                        <div class="review-card-top">
                            <div class="user-info review-user">
                                <div class="user-avatar review-avatar">{{ substr($review->user->name, 0, 1) }}</div>
                                <div class="review-user-meta">
                                    <div class="user-name">{{ $review->user->name }}</div>
                                    <div class="review-date">{{ $review->created_at->format('M d, Y') }}</div>
                                </div>
                            </div>

                            <div class="review-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <svg class="star star-filled" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @else
                                        <svg class="star" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                        </svg>
                                    @endif
                                @endfor
                                <span class="rating-value">{{ $review->rating }}/5</span>
                            </div>
                        </div>

                        <div class="review-badges">
                            <span class="badge {{ $review->is_approved ? 'badge-approved' : 'badge-pending' }}">
                                {{ $review->is_approved ? 'Approved' : 'Pending Review' }}
                            </span>
                            @if($review->is_verified)
                                <span class="badge badge-verified">Verified Stay</span>
                            @endif
                        </div>

                        <div class="review-property">
                            <svg class="review-property-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21s7-5.686 7-12A7 7 0 105 9c0 6.314 7 12 7 12z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9a2 2 0 110 4 2 2 0 010-4z"></path>
                            </svg>
                            <span>{{ $review->property->title }}</span>
                        </div>

                        <div class="review-text">
                            {{ $review->review_text ?? $review->comment ?? 'No comment provided' }}
                        </div>
                    </article>
                @endforeach

                <div class="pagination-wrapper">
                    {{ $reviews->links() }}
                </div>
            @else
                <div class="empty-state">
                    <h3>No Reviews Yet</h3>
                    <p>Your properties haven't received any reviews yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
