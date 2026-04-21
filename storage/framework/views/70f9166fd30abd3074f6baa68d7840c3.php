<?php $__env->startSection('title', 'Users'); ?>
<?php $__env->startSection('page_title', 'Users'); ?>
<?php $__env->startSection('hide_pagebar', 'true'); ?>

<?php $__env->startSection('content'); ?>
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
                <form method="GET" action="<?php echo e(route('admin.users.index')); ?>" class="admin-filters">
                    <div class="admin-filter-group">
                        <label for="role">Role</label>
                        <select id="role" name="role" class="admin-input">
                            <option value="">All Roles</option>
                            <option value="user" <?php if(request('role') === 'user'): echo 'selected'; endif; ?>>User</option>
                            <option value="owner" <?php if(request('role') === 'owner'): echo 'selected'; endif; ?>>Owner</option>
                            <option value="admin" <?php if(request('role') === 'admin'): echo 'selected'; endif; ?>>Admin</option>
                        </select>
                    </div>

                    <div class="admin-filter-actions">
                        <button type="submit" class="admin-btn admin-btn-primary">Apply Filter</button>
                        <a href="<?php echo e(route('admin.users.index')); ?>" class="admin-btn admin-btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </section>

        <section class="content-card admin-users-results-card">
            <div class="content-card-header admin-panel-header">
                <div>
                    <h2>All Users</h2>
                    <p><?php echo e($users->total()); ?> account<?php echo e($users->total() === 1 ? '' : 's'); ?> found.</p>
                </div>

                <span class="admin-card-chip">View user details</span>
            </div>

            <div class="admin-users-grid">
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php ($userAvatarUrl = $user->profilePhotoUrl()); ?>

                    <article class="admin-user-card">
                        <div class="admin-user-card-top">
                            <div class="admin-user-card-avatar">
                                <?php if($userAvatarUrl): ?>
                                    <img
                                        src="<?php echo e($userAvatarUrl); ?>"
                                        alt="<?php echo e($user->name); ?>"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                    >
                                    <span class="admin-user-card-avatar-fallback" style="display:none;"><?php echo e($user->avatarInitial()); ?></span>
                                <?php else: ?>
                                    <span class="admin-user-card-avatar-fallback"><?php echo e($user->avatarInitial()); ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="admin-user-card-copy">
                                <h3><?php echo e($user->name); ?></h3>
                                <p><?php echo e($user->email); ?></p>
                                <span><?php echo e($user->phone ?: 'No phone number'); ?></span>
                            </div>
                        </div>

                        <div class="admin-user-card-badges">
                            <span class="status-pill status-neutral"><?php echo e(ucfirst($user->role)); ?></span>
                            <span class="status-pill <?php echo e($user->is_verified ? 'status-approved' : 'status-neutral'); ?>">
                                <?php echo e($user->is_verified ? 'Verified' : 'Unverified'); ?>

                            </span>
                            <span class="status-pill <?php echo e($user->is_blocked ? 'status-rejected' : 'status-approved'); ?>">
                                <?php echo e($user->is_blocked ? 'Blocked' : 'Active'); ?>

                            </span>
                        </div>

                        <div class="admin-user-card-body">
                            <div class="admin-user-summary-grid admin-user-summary-grid-index">
                                <div class="admin-user-mini-stat">
                                    <span>Bookings</span>
                                    <strong><?php echo e($user->bookings_count); ?></strong>
                                </div>

                                <div class="admin-user-mini-stat">
                                    <span>Joined</span>
                                    <strong><?php echo e(optional($user->created_at)->format('M d, Y') ?? 'N/A'); ?></strong>
                                </div>
                            </div>

                            <div class="admin-user-card-actions">
                                <a href="<?php echo e(route('admin.users.show', $user)); ?>" class="admin-btn admin-btn-secondary">
                                    View user details
                                </a>

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
                            </div>
                        </div>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="admin-users-empty">No users matched the current filter.</div>
                <?php endif; ?>
            </div>

            <?php if($users->hasPages()): ?>
                <div class="admin-properties-pagination">
                    <?php echo e($users->links()); ?>

                </div>
            <?php endif; ?>
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\FindNest\resources\views/admin/users/index.blade.php ENDPATH**/ ?>