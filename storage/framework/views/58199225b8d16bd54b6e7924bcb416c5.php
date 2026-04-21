<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="owner-dashboard-page">
    <section class="owner-dashboard-hero">
        <div class="owner-dashboard-copy-block">
            <p class="owner-dashboard-kicker">Overview</p>
            <h2 class="owner-dashboard-title">Dashboard</h2>
            <p class="owner-dashboard-copy">Manage listings, bookings, and activity.</p>
        </div>

        <a href="<?php echo e(route('owner.listings.create')); ?>" class="owner-dashboard-cta">Add Property</a>
    </section>

    <section class="owner-stat-grid" aria-label="Dashboard metrics">
        <article class="owner-stat-card owner-stat-card--neutral">
            <p class="owner-stat-label">Total Properties</p>
            <div class="owner-stat-value"><?php echo e($totalListings); ?></div>
            <p class="owner-stat-note">All properties in your portfolio</p>
        </article>

        <article class="owner-stat-card owner-stat-card--active">
            <p class="owner-stat-label">Active Properties</p>
            <div class="owner-stat-value"><?php echo e($activeListings); ?></div>
            <p class="owner-stat-note">Currently visible and bookable</p>
        </article>

        <article class="owner-stat-card owner-stat-card--pending">
            <p class="owner-stat-label">Pending Requests</p>
            <div class="owner-stat-value"><?php echo e($pendingBookingRequests); ?></div>
            <p class="owner-stat-note">Needs review or action</p>
        </article>

        <article class="owner-stat-card owner-stat-card--rating">
            <p class="owner-stat-label">Average Rating</p>
            <div class="owner-stat-value"><?php echo e(number_format($avgRating, 1)); ?></div>
            <p class="owner-stat-note"><?php echo e($reviewsCount); ?> <?php echo e($reviewsCount === 1 ? 'review' : 'reviews'); ?></p>
        </article>
    </section>

    <section class="owner-section-card owner-booking-section">
        <div class="owner-section-header">
            <div>
                <h3 class="owner-section-title">Management Actions</h3>
                <p class="owner-section-copy">Quick access to the most common owner tasks.</p>
            </div>
        </div>

        <div class="owner-action-grid" aria-label="Quick actions">
            <a href="<?php echo e(route('owner.listings.index')); ?>" class="owner-action-card">
                <span class="owner-action-icon" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10.5V20h16v-9.5M4 10.5L12 4l8 6.5M9 20v-6h6v6"></path>
                    </svg>
                </span>
                <span>
                    <span class="owner-action-title">Properties</span>
                    <span class="owner-action-copy">View and manage your current property listings.</span>
                </span>
            </a>

            <a href="<?php echo e(route('owner.listings.create')); ?>" class="owner-action-card">
                <span class="owner-action-icon" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </span>
                <span>
                    <span class="owner-action-title">Add Property</span>
                    <span class="owner-action-copy">Create a new property listing from scratch.</span>
                </span>
            </a>

            <a href="<?php echo e(route('owner.bookings.index')); ?>" class="owner-action-card">
                <span class="owner-action-icon" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </span>
                <span>
                    <span class="owner-action-title">Booking Requests</span>
                    <span class="owner-action-copy">Review booking activity and respond quickly.</span>
                </span>
            </a>
        </div>
    </section>

    <section class="owner-section-card">
        <div class="owner-section-header">
            <div>
                <h3 class="owner-section-title">Recent Booking Requests</h3>
                <p class="owner-section-copy">Latest requests that need your attention.</p>
            </div>

            <a href="<?php echo e(route('owner.bookings.index')); ?>" class="btn-secondary-sm">View All</a>
        </div>

        <?php if($recentBookings->count() > 0): ?>
            <div class="owner-activity-head" aria-hidden="true">
                <span>User</span>
                <span>Property</span>
                <span>Check-in</span>
                <span>Status</span>
                <span>Actions</span>
            </div>

            <div class="owner-activity-list">
                <?php $__currentLoopData = $recentBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $bookingStatusLabel = match ($booking->status) {
                            'pending' => 'Awaiting response',
                            'confirmed' => 'Confirmed',
                            'cancelled' => 'Cancelled',
                            'rejected' => 'Rejected',
                            default => ucfirst($booking->status),
                        };
                    ?>

                    <div class="owner-activity-row">
                        <div class="owner-user">
                            <div class="owner-avatar"><?php echo e(substr($booking->user->name, 0, 1)); ?></div>
                            <div class="owner-user-meta">
                                <div class="owner-user-name"><?php echo e($booking->user->name); ?></div>
                                <div class="owner-user-email"><?php echo e($booking->user->email); ?></div>
                            </div>
                        </div>

                        <div class="owner-property-cell">
                            <div class="owner-property-title" title="<?php echo e($booking->property->title); ?>"><?php echo e($booking->property->title); ?></div>
                            <div class="owner-property-subtitle"><?php echo e($booking->property->city ?? 'Property'); ?></div>
                        </div>

                        <div>
                            <div class="owner-meta-value"><?php echo e(\Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y')); ?></div>
                        </div>

                        <div>
                            <div class="owner-status-badge-wrap">
                                <span class="badge badge-<?php echo e($booking->status); ?>">
                                    <?php echo e($bookingStatusLabel); ?>

                                </span>
                            </div>
                        </div>

                        <div class="owner-activity-actions">
                            <?php if($booking->status === 'pending'): ?>
                                <form method="POST" action="<?php echo e(route('owner.bookings.accept', $booking->id)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-success-outline">Accept</button>
                                </form>
                                <form method="POST" action="<?php echo e(route('owner.bookings.reject', $booking->id)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-danger-outline">Reject</button>
                                </form>
                            <?php else: ?>
                                <span class="table-empty-action">
                                    <?php if($booking->confirmed_at): ?>
                                        Confirmed on <?php echo e(\Carbon\Carbon::parse($booking->confirmed_at)->format('M d, Y')); ?>

                                    <?php elseif($booking->cancelled_at): ?>
                                        Cancelled on <?php echo e(\Carbon\Carbon::parse($booking->cancelled_at)->format('M d, Y')); ?>

                                    <?php else: ?>
                                        No further actions
                                    <?php endif; ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="owner-empty-state">
                <h3>No Booking Requests Yet</h3>
                <p>When guests book your properties, they will appear here.</p>
            </div>
        <?php endif; ?>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('owner.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\FindNest\resources\views/owner/dashboard.blade.php ENDPATH**/ ?>