<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page_kicker', 'Overview'); ?>
<?php $__env->startSection('page_title', 'Admin Dashboard'); ?>
<?php $__env->startSection('page_meta'); ?>
    <span class="admin-page-date">
        <?php echo e(now()->format('l, F j, Y')); ?>

        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="admin-dashboard admin-dashboard-home">
        <section class="stats-grid admin-dashboard-stats">
            <article class="stat-card">
                <p class="stat-label">Total Users</p>
                <p class="stat-value"><?php echo e($totalUsers); ?></p>
            </article>
            <article class="stat-card">
                <p class="stat-label">Total Owners</p>
                <p class="stat-value"><?php echo e($totalOwners); ?></p>
            </article>
            <article class="stat-card">
                <p class="stat-label">Total Properties</p>
                <p class="stat-value"><?php echo e($totalProperties); ?></p>
            </article>
            <article class="stat-card stat-card-warn">
                <p class="stat-label">Pending Properties</p>
                <p class="stat-value"><?php echo e($pendingProperties); ?></p>
            </article>
            <article class="stat-card stat-card-good">
                <p class="stat-label">Approved Properties</p>
                <p class="stat-value"><?php echo e($approvedProperties); ?></p>
            </article>
            <article class="stat-card stat-card-danger">
                <p class="stat-label">Rejected Properties</p>
                <p class="stat-value"><?php echo e($rejectedProperties); ?></p>
            </article>
            <article class="stat-card">
                <p class="stat-label">Total Revenue</p>
                <p class="stat-value stat-value-currency">NPR <?php echo e(number_format($totalRevenue)); ?></p>
            </article>
            <article class="stat-card">
                <p class="stat-label">Total Reviews</p>
                <p class="stat-value"><?php echo e($totalReviews); ?></p>
            </article>
        </section>

        <section class="admin-dashboard-top-grid">
            <article class="content-card admin-dashboard-panel admin-chart-panel">
                <div class="content-card-header admin-panel-header">
                    <div>
                        <h2>Platform Overview</h2>
                        <p>Live signup, listing, and booking activity from the first record onward.</p>
                    </div>

                    <div class="admin-chart-legend">
                        <?php $__currentLoopData = $activityChart['series']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $series): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="admin-legend-item">
                                <span class="admin-legend-dot" style="background: <?php echo e($series['color']); ?>"></span>
                                <?php echo e($series['label']); ?>

                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <div class="admin-chart-shell">
                    <svg viewBox="0 0 <?php echo e($activityChart['width']); ?> <?php echo e($activityChart['height']); ?>" class="admin-chart-svg" role="img" aria-label="Platform activity chart">
                        <?php $__currentLoopData = $activityChart['ticks']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $tick): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $tickCount = max(count($activityChart['ticks']) - 1, 1);
                                $y = $activityChart['padding'] + (($activityChart['height'] - ($activityChart['padding'] * 2)) * ($index / $tickCount));
                            ?>
                            <line x1="<?php echo e($activityChart['padding']); ?>" y1="<?php echo e($y); ?>" x2="<?php echo e($activityChart['width'] - $activityChart['padding']); ?>" y2="<?php echo e($y); ?>" class="admin-chart-grid-line"></line>
                            <text x="10" y="<?php echo e($y + 4); ?>" class="admin-chart-axis-label"><?php echo e(number_format($tick)); ?></text>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php $__currentLoopData = $activityChart['labels']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $labelCount = max(count($activityChart['labels']) - 1, 1);
                                $x = count($activityChart['labels']) === 1
                                    ? ($activityChart['width'] / 2)
                                    : $activityChart['padding'] + (($activityChart['width'] - ($activityChart['padding'] * 2)) * ($index / $labelCount));
                            ?>
                            <text x="<?php echo e($x); ?>" y="<?php echo e($activityChart['height'] - 6); ?>" text-anchor="middle" class="admin-chart-month-label"><?php echo e($label); ?></text>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php $__currentLoopData = $activityChart['series']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $series): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <path d="<?php echo e($series['area']); ?>" fill="<?php echo e($series['fill']); ?>" opacity="0.95"></path>
                            <path d="<?php echo e($series['line']); ?>" fill="none" stroke="<?php echo e($series['color']); ?>" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
                            <?php $__currentLoopData = $series['points']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $point): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <circle cx="<?php echo e($point['x']); ?>" cy="<?php echo e($point['y']); ?>" r="3.5" fill="<?php echo e($series['color']); ?>" stroke="#ffffff" stroke-width="2"></circle>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </svg>
                </div>
            </article>

            <article class="content-card admin-dashboard-panel admin-donut-panel">
                <div class="content-card-header admin-panel-header">
                    <div>
                        <h2>Booking Status</h2>
                        <p>Current booking distribution.</p>
                    </div>

                    <span class="admin-card-chip"><?php echo e(number_format($bookingChart['total'])); ?> total</span>
                </div>

                <div class="admin-donut-shell">
                    <div class="admin-donut-chart" style="background: <?php echo e($bookingChart['background']); ?>;">
                        <div class="admin-donut-center">
                            <strong><?php echo e(number_format($bookingChart['total'])); ?></strong>
                            <span>Bookings</span>
                        </div>
                    </div>

                    <div class="admin-donut-legend">
                        <?php $__empty_1 = true; $__currentLoopData = $bookingChart['segments']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $segment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="admin-donut-legend-item">
                                <span class="admin-legend-dot" style="background: <?php echo e($segment['color']); ?>"></span>
                                <div>
                                    <strong><?php echo e($segment['label']); ?></strong>
                                    <span><?php echo e($segment['percentage']); ?>% &middot; <?php echo e($segment['count']); ?></span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="admin-empty-note">No bookings yet.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </article>

        </section>

        <section class="admin-dashboard-bottom">
            <article class="content-card admin-dashboard-table-card">
                <div class="content-card-header admin-panel-header">
                    <div>
                        <h2>Recent Properties</h2>
                        <p>Latest property submissions and updates.</p>
                    </div>

                </div>

                <div class="table-wrap">
                    <table class="admin-table admin-dashboard-table">
                        <thead>
                            <tr>
                                <th>Property</th>
                                <th>Owner</th>
                                <th>City</th>
                                <th>Status</th>
                                <th>Verified</th>
                                <th>Added</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $recentProperties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <div class="primary-text"><?php echo e($property->title); ?></div>
                                        <div class="muted-text"><?php echo e(\Illuminate\Support\Str::limit($property->address, 40)); ?></div>
                                    </td>
                                    <td><?php echo e($property->owner->name ?? 'N/A'); ?></td>
                                    <td><?php echo e($property->city ?? 'N/A'); ?></td>
                                    <td>
                                        <span class="status-pill status-<?php echo e($property->status); ?>">
                                            <?php echo e(ucfirst($property->status)); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-pill <?php echo e($property->is_verified ? 'status-approved' : 'status-neutral'); ?>">
                                            <?php echo e($property->is_verified ? 'Verified' : 'Unverified'); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e(optional($property->created_at)->format('M d, Y') ?? 'N/A'); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="empty-cell">No recent properties found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="admin-card-footer">
                    <a href="<?php echo e(route('admin.properties.index')); ?>" class="admin-footer-link">View all properties</a>
                </div>
            </article>

            <article class="content-card admin-dashboard-table-card">
                <div class="content-card-header admin-panel-header">
                    <div>
                        <h2>Recent Bookings</h2>
                        <p>Latest booking activity across the platform.</p>
                    </div>

                </div>

                <div class="table-wrap">
                    <table class="admin-table admin-dashboard-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Property</th>
                                <th>Status</th>
                                <th>Check In</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $recentBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($booking->user->name ?? 'N/A'); ?></td>
                                    <td><?php echo e($booking->property->title ?? 'N/A'); ?></td>
                                    <td>
                                        <span class="status-pill status-<?php echo e($booking->status); ?>">
                                            <?php echo e(ucfirst($booking->status ?? 'N/A')); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e(optional($booking->check_in_date)->format('M d, Y') ?? 'N/A'); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="empty-cell">No recent bookings found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="admin-card-footer">
                    <a href="<?php echo e(route('admin.bookings.index')); ?>" class="admin-footer-link">View all bookings</a>
                </div>
            </article>
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\FindNest\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>