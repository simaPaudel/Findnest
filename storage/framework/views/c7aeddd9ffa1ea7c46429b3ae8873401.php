<?php
    $notificationUser = null;

    try {
        if (auth()->check()) {
            $notificationUser = auth()->user();
        }
    } catch (\Throwable $e) {
        $notificationUser = null;
    }
?>

<?php if($notificationUser): ?>
    <?php
        $notifications = $recentNotifications ?? collect();
        $unreadCount = (int) ($notificationCount ?? 0);
    ?>

    <details class="fn-notification-menu">
        <summary class="fn-notification-trigger" title="Notifications" aria-label="Notifications">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0m6 0H9"></path>
            </svg>

            <span class="fn-notification-badge" <?php if($unreadCount < 1): ?> hidden <?php endif; ?>>
                <?php echo e($unreadCount > 99 ? '99+' : $unreadCount); ?>

            </span>
        </summary>

        <div class="fn-notification-panel" role="menu" aria-label="Recent notifications">
            <div class="fn-notification-panel-header">
                <div>
                    <p class="fn-notification-kicker">Notifications</p>
                    <h3>Recent updates</h3>
                </div>

                <?php if($unreadCount > 0): ?>
                    <span class="fn-notification-summary"><?php echo e($unreadCount); ?> unread</span>
                <?php endif; ?>
            </div>

            <div class="fn-notification-list">
                <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a href="<?php echo e(route('notifications.open', $notification)); ?>" class="fn-notification-item <?php echo e($notification->is_read ? '' : 'is-unread'); ?>">
                        <div class="fn-notification-item-head">
                            <strong><?php echo e($notification->title); ?></strong>
                            <span><?php echo e(optional($notification->created_at)->diffForHumans()); ?></span>
                        </div>
                        <p><?php echo e(\Illuminate\Support\Str::limit($notification->message, 90)); ?></p>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="fn-notification-empty">
                        No notifications yet.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </details>
<?php endif; ?>

<style>
    .fn-notification-menu {
        position: relative;
        display: inline-flex;
        align-items: center;
    }

    .fn-notification-menu > summary {
        list-style: none;
    }

    .fn-notification-menu > summary::-webkit-details-marker {
        display: none;
    }

    .fn-notification-trigger {
        position: relative;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: 1px solid var(--fn-gray-border, #e5e7eb);
        background: #fff;
        color: var(--fn-charcoal, #1f2937);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .fn-notification-trigger svg {
        width: 1.05rem;
        height: 1.05rem;
    }

    .fn-notification-trigger:hover,
    .fn-notification-menu[open] .fn-notification-trigger {
        border-color: rgba(255, 56, 92, 0.25);
        color: var(--fn-red, #ff385c);
        background: rgba(255, 56, 92, 0.06);
    }

    .fn-notification-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        min-width: 16px;
        height: 16px;
        border-radius: 999px;
        background: var(--fn-red, #ff385c);
        color: #fff;
        font-size: 0.64rem;
        font-weight: 700;
        line-height: 16px;
        text-align: center;
        padding: 0 4px;
        border: 2px solid #fff;
    }

    .fn-notification-panel {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        width: 360px;
        max-width: min(360px, calc(100vw - 24px));
        max-height: 420px;
        overflow: hidden;
        border: 1px solid var(--fn-gray-border, #e5e7eb);
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.12);
        z-index: 1200;
    }

    .fn-notification-panel-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        padding: 16px 16px 12px;
        border-bottom: 1px solid #f1f5f9;
    }

    .fn-notification-kicker {
        margin: 0;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: var(--fn-gray-dark, #64748b);
    }

    .fn-notification-panel-header h3 {
        margin: 4px 0 0;
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--fn-charcoal, #1f2937);
    }

    .fn-notification-summary {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 999px;
        background: rgba(255, 56, 92, 0.08);
        color: var(--fn-red, #ff385c);
        font-size: 0.72rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .fn-notification-list {
        max-height: 360px;
        overflow-y: auto;
    }

    .fn-notification-item {
        display: block;
        padding: 14px 16px;
        border-bottom: 1px solid #f8fafc;
        text-decoration: none;
        color: inherit;
        transition: background 0.18s ease;
    }

    .fn-notification-item:hover {
        background: #fafafa;
    }

    .fn-notification-item.is-unread {
        background: rgba(255, 56, 92, 0.04);
    }

    .fn-notification-item-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
    }

    .fn-notification-item-head strong {
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--fn-charcoal, #1f2937);
    }

    .fn-notification-item-head span {
        font-size: 0.72rem;
        color: var(--fn-gray-dark, #64748b);
        white-space: nowrap;
        flex-shrink: 0;
    }

    .fn-notification-item p {
        margin: 6px 0 0;
        font-size: 0.82rem;
        line-height: 1.5;
        color: var(--fn-gray-dark, #64748b);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .fn-notification-empty {
        padding: 18px 16px;
        font-size: 0.85rem;
        color: var(--fn-gray-dark, #64748b);
    }

    @media (max-width: 520px) {
        .fn-notification-panel {
            right: -6px;
            width: min(340px, calc(100vw - 18px));
        }
    }
</style>
<?php /**PATH C:\xampp\htdocs\FindNest\resources\views/components/notification-dropdown.blade.php ENDPATH**/ ?>