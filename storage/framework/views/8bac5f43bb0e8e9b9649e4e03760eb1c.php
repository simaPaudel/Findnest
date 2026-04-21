<?php $__env->startSection('title', $user->name . ' | Profile'); ?>
<?php $__env->startSection('hide_pagebar', 'true'); ?>

<?php $__env->startSection('content'); ?>
    <div class="admin-dashboard admin-user-detail-page">
        <section class="content-card admin-user-profile-card">
            <div class="admin-user-profile-main">
                <div class="admin-user-profile-avatar">
                    <?php if($user->profilePhotoUrl()): ?>
                        <img
                            src="<?php echo e($user->profilePhotoUrl()); ?>"
                            alt="<?php echo e($user->name); ?>"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                        >
                        <span class="admin-user-profile-avatar-fallback" style="display:none;"><?php echo e($user->avatarInitial()); ?></span>
                    <?php else: ?>
                        <span class="admin-user-profile-avatar-fallback"><?php echo e($user->avatarInitial()); ?></span>
                    <?php endif; ?>
                </div>

                <div class="admin-user-profile-copy">
                    <p class="admin-user-profile-kicker">Profile overview</p>
                    <h2><?php echo e($user->name); ?></h2>
                    <p><?php echo e($user->email); ?></p>

                    <div class="admin-user-profile-badges">
                        <span class="status-pill status-neutral"><?php echo e(ucfirst($user->role)); ?></span>
                        <span class="status-pill <?php echo e($user->is_verified ? 'status-approved' : 'status-neutral'); ?>">
                            <?php echo e($user->is_verified ? 'Verified' : 'Unverified'); ?>

                        </span>
                        <span class="status-pill <?php echo e($user->is_blocked ? 'status-rejected' : 'status-approved'); ?>">
                            <?php echo e($user->is_blocked ? 'Blocked' : 'Active'); ?>

                        </span>
                    </div>
                </div>
            </div>

            <div class="admin-user-profile-actions">
                <?php if(! $user->isAdmin()): ?>
                    <form method="POST" action="<?php echo e(route('admin.users.toggle-status', $user)); ?>">
                        <?php echo csrf_field(); ?>
                        <button
                            type="submit"
                            class="admin-btn <?php echo e($user->is_blocked ? 'admin-btn-success' : 'admin-btn-danger'); ?>"
                        >
                            <?php echo e($user->is_blocked ? 'Reactivate user' : 'Block user'); ?>

                        </button>
                    </form>
                <?php else: ?>
                    <span class="admin-meta-note">Admin accounts are protected.</span>
                <?php endif; ?>

                <a href="<?php echo e(route('admin.users.index')); ?>" class="admin-btn admin-btn-secondary">Back to users</a>
            </div>
        </section>

        <section class="admin-user-stats-grid">
            <article class="content-card admin-user-stat-card">
                <span class="admin-user-stat-label">Bookings</span>
                <strong><?php echo e($user->bookings_count); ?></strong>
                <p>Total bookings linked to this account.</p>
            </article>

            <article class="content-card admin-user-stat-card">
                <span class="admin-user-stat-label">Payments</span>
                <strong>Rs <?php echo e(number_format((float) $successfulPaymentsAmount, 2)); ?></strong>
                <p><?php echo e($successfulPaymentsCount); ?> successful payment<?php echo e($successfulPaymentsCount === 1 ? '' : 's'); ?>.</p>
            </article>

            <article class="content-card admin-user-stat-card">
                <span class="admin-user-stat-label">Reviews</span>
                <strong><?php echo e($user->reviews_count); ?></strong>
                <p>Reviews written by this user.</p>
            </article>

            <article class="content-card admin-user-stat-card">
                <span class="admin-user-stat-label">Properties</span>
                <strong><?php echo e($user->properties_count); ?></strong>
                <p>Properties owned by this user.</p>
            </article>
        </section>

        <section class="admin-user-info-grid">
            <article class="content-card">
                <div class="content-card-header admin-panel-header">
                    <div>
                        <h2>Basic info</h2>
                        <p>Account and contact details.</p>
                    </div>
                </div>

                <div class="admin-detail-list">
                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Name</span>
                        <span class="admin-detail-value"><?php echo e($user->name); ?></span>
                    </div>

                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Email</span>
                        <span class="admin-detail-value"><?php echo e($user->email); ?></span>
                    </div>

                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Phone</span>
                        <span class="admin-detail-value"><?php echo e($user->phone ?: 'N/A'); ?></span>
                    </div>

                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Role</span>
                        <span class="admin-detail-value"><?php echo e(ucfirst($user->role)); ?></span>
                    </div>

                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Gender</span>
                        <span class="admin-detail-value"><?php echo e($user->gender ? ucfirst($user->gender) : 'N/A'); ?></span>
                    </div>

                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Joined</span>
                        <span class="admin-detail-value"><?php echo e(optional($user->created_at)->format('M d, Y') ?? 'N/A'); ?></span>
                    </div>

                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Status</span>
                        <span class="admin-detail-value"><?php echo e($user->is_blocked ? 'Blocked' : 'Active'); ?></span>
                    </div>

                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Bio</span>
                        <span class="admin-detail-value"><?php echo e($user->bio ?: 'No bio added.'); ?></span>
                    </div>
                </div>
            </article>

            <article class="content-card admin-user-actions-card">
                <div class="content-card-header admin-panel-header">
                    <div>
                        <h2>Admin actions</h2>
                        <p>Manage account access and role from one place.</p>
                    </div>
                </div>

                <div class="admin-user-actions-body">
                    <?php if(! $user->isAdmin() && (int) auth()->id() !== (int) $user->id): ?>
                        <form method="POST" action="<?php echo e(route('admin.users.update-role', $user)); ?>" class="admin-user-role-form">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <label for="role" class="admin-detail-label">Manage role</label>
                            <div class="admin-user-role-row">
                                <select id="role" name="role" class="admin-input">
                                    <option value="user" <?php if($user->role === 'user'): echo 'selected'; endif; ?>>User</option>
                                    <option value="owner" <?php if($user->role === 'owner'): echo 'selected'; endif; ?>>Owner</option>
                                </select>
                                <button type="submit" class="admin-btn admin-btn-secondary">Save role</button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="admin-empty-note">
                            Role changes are protected for this account.
                        </div>
                    <?php endif; ?>

                    <div class="admin-user-access-actions">
                        <?php if(! $user->isAdmin()): ?>
                            <form method="POST" action="<?php echo e(route('admin.users.toggle-status', $user)); ?>">
                                <?php echo csrf_field(); ?>
                                <button
                                    type="submit"
                                    class="admin-btn <?php echo e($user->is_blocked ? 'admin-btn-success' : 'admin-btn-danger'); ?>"
                                >
                                    <?php echo e($user->is_blocked ? 'Reactivate user' : 'Block user'); ?>

                                </button>
                            </form>
                        <?php endif; ?>

                        <a href="<?php echo e(route('admin.users.index')); ?>" class="admin-btn admin-btn-secondary">View all users</a>
                    </div>
                </div>
            </article>
        </section>

        <section class="admin-user-records-grid">
            <?php if($user->isOwner()): ?>
                <article class="content-card admin-user-properties-card">
                    <div class="content-card-header admin-panel-header">
                        <div>
                            <h2>Properties</h2>
                            <p>Latest three properties owned by this account.</p>
                        </div>

                        <a href="<?php echo e(route('admin.properties.index', ['user' => $user->id])); ?>" class="admin-btn admin-btn-secondary admin-btn-sm">View all properties</a>
                    </div>

                    <div class="admin-user-record-list">
                        <?php if($recentProperties->isNotEmpty()): ?>
                            <?php $__currentLoopData = $recentProperties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <article class="admin-user-record-item">
                                    <div class="admin-user-record-media">
                                        <img
                                            src="<?php echo e($property->getFirstImageUrl() ?? asset('images/property-placeholder.jpg')); ?>"
                                            alt="<?php echo e($property->title); ?>"
                                            onerror="this.src='<?php echo e(asset('images/property-placeholder.jpg')); ?>';"
                                        >
                                    </div>

                                    <div class="admin-user-record-copy">
                                        <strong><?php echo e(\Illuminate\Support\Str::limit($property->title, 44)); ?></strong>
                                        <p><?php echo e($property->city ?: 'N/A'); ?> &middot; <?php echo e($property->getPropertyTypeLabel()); ?></p>
                                        <span class="status-pill <?php echo e($property->is_verified ? 'status-approved' : 'status-neutral'); ?>">
                                            <?php echo e($property->is_verified ? 'Verified' : ucfirst($property->status ?? 'Pending')); ?>

                                        </span>
                                    </div>

                                    <a href="<?php echo e(route('listings.show', $property)); ?>" class="admin-btn admin-btn-secondary admin-user-record-view">View</a>
                                </article>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <div class="admin-user-record-empty">No properties found for this owner.</div>
                        <?php endif; ?>
                    </div>
                </article>
            <?php else: ?>
                <article class="content-card admin-user-bookings-card">
                    <div class="content-card-header admin-panel-header">
                        <div>
                            <h2>Booking history</h2>
                            <p>Latest three bookings tied to this account.</p>
                        </div>

                        <a href="<?php echo e(route('admin.bookings.index', ['user' => $user->id])); ?>" class="admin-btn admin-btn-secondary admin-btn-sm">View all bookings</a>
                    </div>

                    <div class="table-wrap admin-user-table-wrap">
                        <table class="admin-table admin-user-bookings-table">
                            <thead>
                                <tr>
                                    <th>Booking</th>
                                    <th>Property</th>
                                    <th>Stay</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($recentBookings->isNotEmpty()): ?>
                                    <?php $__currentLoopData = $recentBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <div class="admin-booking-reference">
                                                    <strong>#<?php echo e($booking->id); ?></strong>
                                                    <p><?php echo e(optional($booking->created_at)->format('M d, Y') ?? 'N/A'); ?></p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-booking-property">
                                                    <div class="admin-booking-property-thumb">
                                                        <img
                                                            src="<?php echo e(optional($booking->property)->getFirstImageUrl() ?? asset('images/property-placeholder.jpg')); ?>"
                                                            alt="<?php echo e(optional($booking->property)->title ?? 'Booking property'); ?>"
                                                            onerror="this.src='<?php echo e(asset('images/property-placeholder.jpg')); ?>';"
                                                        >
                                                    </div>
                                                    <div class="admin-booking-property-copy">
                                                        <strong><?php echo e(\Illuminate\Support\Str::limit(optional($booking->property)->title ?? 'N/A', 42)); ?></strong>
                                                        <p><?php echo e(optional($booking->property)->city ?: 'N/A'); ?></p>
                                                        <span><?php echo e($booking->getBookableTypeLabel()); ?></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-booking-stay">
                                                    <strong><?php echo e(optional($booking->check_in_date)->format('M d, Y') ?? 'N/A'); ?></strong>
                                                    <p>to <?php echo e(optional($booking->check_out_date)->format('M d, Y') ?? 'N/A'); ?></p>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="status-pill <?php echo e($booking->isConfirmed() ? 'status-approved' : ($booking->isCancelled() ? 'status-rejected' : 'status-pending')); ?>">
                                                    <?php echo e($booking->getStatusLabel()); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <span class="status-pill <?php echo e($booking->hasSuccessfulPayment() ? 'status-approved' : ($booking->hasPendingPayment() ? 'status-pending' : 'status-neutral')); ?>">
                                                    <?php echo e($booking->hasSuccessfulPayment() ? 'Paid' : ($booking->hasPendingPayment() ? 'Pending' : 'No payment')); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?php echo e(route('admin.bookings.show', $booking)); ?>" class="admin-btn admin-btn-secondary admin-btn-sm">View</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="empty-cell">No bookings found for this user.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </article>
            <?php endif; ?>

            <article class="content-card admin-user-reviews-card">
                <div class="content-card-header admin-panel-header">
                    <div>
                        <h2>Reviews</h2>
                        <p>Latest reviews written by this user.</p>
                    </div>

                    <a href="<?php echo e(route('admin.reviews.index', ['user' => $user->id])); ?>" class="admin-btn admin-btn-secondary admin-btn-sm">View all reviews</a>
                </div>

                <div class="admin-user-record-list">
                    <?php if($recentReviews->isNotEmpty()): ?>
                        <?php $__currentLoopData = $recentReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <article class="admin-user-record-item">
                                <div class="admin-user-record-media">
                                    <img
                                        src="<?php echo e(optional($review->property)->getFirstImageUrl() ?? asset('images/property-placeholder.jpg')); ?>"
                                        alt="<?php echo e(optional($review->property)->title ?? 'Review property'); ?>"
                                        onerror="this.src='<?php echo e(asset('images/property-placeholder.jpg')); ?>';"
                                    >
                                </div>

                                <div class="admin-user-record-copy">
                                    <strong><?php echo e(\Illuminate\Support\Str::limit(optional($review->property)->title ?? 'N/A', 44)); ?></strong>
                                    <p><?php echo e(\Illuminate\Support\Str::limit($review->review_text, 78)); ?></p>
                                    <span class="admin-user-record-rating"><?php echo e($review->rating); ?>/5</span>
                                </div>

                                <a href="<?php echo e($review->property ? route('listings.show', $review->property) : route('admin.reviews.index')); ?>" class="admin-btn admin-btn-secondary admin-user-record-view">View</a>
                            </article>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="admin-user-record-empty">No reviews found for this user.</div>
                    <?php endif; ?>
                </div>
            </article>

            <article class="content-card admin-user-reports-card">
                <div class="content-card-header admin-panel-header">
                    <div>
                        <h2>Reports</h2>
                        <p>Recent reports filed by or about this user.</p>
                    </div>

                    <a href="<?php echo e(route('admin.reports.index', ['user' => $user->id])); ?>" class="admin-btn admin-btn-secondary admin-btn-sm">View all reports</a>
                </div>

                <div class="admin-user-record-list">
                    <?php if($recentReports->isNotEmpty()): ?>
                        <?php $__currentLoopData = $recentReports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <article class="admin-user-record-item">
                                <div class="admin-user-record-copy">
                                    <span class="admin-user-record-rating"><?php echo e($report->getReportTypeLabel()); ?></span>
                                    <strong><?php echo e(\Illuminate\Support\Str::limit($report->reason, 60)); ?></strong>
                                    <p><?php echo e($report->reporter?->name ?? 'Unknown reporter'); ?> &middot; <?php echo e($report->getTargetLabel()); ?></p>
                                </div>

                                <span class="status-pill <?php echo e($report->status === 'resolved' ? 'status-approved' : ($report->status === 'under_review' ? 'status-pending' : 'status-neutral')); ?>">
                                    <?php echo e($report->getStatusLabel()); ?>

                                </span>

                                <a href="<?php echo e(route('admin.reports.show', $report)); ?>" class="admin-btn admin-btn-secondary admin-user-record-view">View</a>
                            </article>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="admin-user-record-empty">No related reports found for this user.</div>
                    <?php endif; ?>
                </div>
            </article>
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\FindNest\resources\views/admin/users/show.blade.php ENDPATH**/ ?>