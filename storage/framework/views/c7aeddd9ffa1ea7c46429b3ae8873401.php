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

        <div class="fn-notification-panel">
            <!-- Header -->
            <div class="fn-notification-header">
                <h2 class="fn-notification-title">Notifications</h2>
                <?php if($unreadCount > 0): ?>
                    <form method="POST" action="<?php echo e(route('notifications.mark-all-read')); ?>" class="fn-notification-mark-all-form">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="fn-header-action-btn" title="Mark all as read">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Mark all</span>
                        </button>
                    </form>
                <?php endif; ?>
            </div>

            <!-- Notifications List -->
            <div class="fn-notification-items">
                <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a href="<?php echo e(route('notifications.open', $notification)); ?>" class="fn-notification-item <?php echo e($notification->is_read ? '' : 'unread'); ?>">
                        <?php if(!$notification->is_read): ?>
                            <div class="fn-unread-indicator"></div>
                        <?php endif; ?>
                        <div class="fn-notification-content">
                            <div class="fn-notification-title-line">
                                <h3 class="fn-notification-item-title"><?php echo e($notification->title); ?></h3>
                                <span class="fn-notification-item-time"><?php echo e(optional($notification->created_at)->diffForHumans()); ?></span>
                            </div>
                            <p class="fn-notification-item-message"><?php echo e(\Illuminate\Support\Str::limit($notification->message, 100)); ?></p>
                        </div>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="fn-notification-empty">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0m6 0H9"></path>
                        </svg>
                        <p>No notifications yet</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Footer -->
            <div class="fn-notification-footer">
                <a href="<?php echo e(route('notifications.index')); ?>" class="fn-see-all-link">
                    View all notifications
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
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
        width: 40px;
        height: 40px;
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

    .fn-notification-trigger:hover {
        border-color: rgba(255, 56, 92, 0.3);
        color: var(--fn-red, #ff385c);
        background: rgba(255, 56, 92, 0.05);
    }

    .fn-notification-menu[open] .fn-notification-trigger {
        border-color: rgba(255, 56, 92, 0.3);
        color: var(--fn-red, #ff385c);
        background: rgba(255, 56, 92, 0.05);
    }

    .fn-notification-trigger svg {
        width: 1.1rem;
        height: 1.1rem;
    }

    .fn-notification-badge {
        position: absolute;
        top: -6px;
        right: -6px;
        min-width: 18px;
        height: 18px;
        border-radius: 50%;
        background: var(--fn-red, #ff385c);
        color: #fff;
        font-size: 0.65rem;
        font-weight: 700;
        line-height: 18px;
        text-align: center;
        padding: 0 4px;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(255, 56, 92, 0.3);
    }

    .fn-notification-panel {
        position: absolute;
        top: calc(100% + 12px);
        right: 0;
        width: 380px;
        max-width: calc(100vw - 20px);
        max-height: 480px;
        background: #fff;
        border: 1px solid var(--fn-gray-border, #e5e7eb);
        border-radius: 10px;
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.1);
        z-index: 1200;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .fn-notification-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px;
        border-bottom: 1px solid #f0f0f0;
        flex-shrink: 0;
    }

    .fn-notification-title {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: var(--fn-charcoal, #1f2937);
        letter-spacing: -0.3px;
    }

    .fn-notification-mark-all-form {
        margin: 0;
    }

    .fn-header-action-btn {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 6px 10px;
        border: none;
        border-radius: 6px;
        background: rgba(255, 56, 92, 0.1);
        color: var(--fn-red, #ff385c);
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.18s ease;
        font-family: inherit;
    }

    .fn-header-action-btn:hover {
        background: rgba(255, 56, 92, 0.15);
    }

    .fn-header-action-btn:active {
        transform: scale(0.98);
    }

    .fn-header-action-btn svg {
        width: 14px;
        height: 14px;
    }

    .fn-notification-items {
        flex: 1;
        min-height: 0;
        overflow-y: auto;
        overscroll-behavior: contain;
    }

    .fn-notification-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 12px 16px;
        border-bottom: 1px solid #f8f8f8;
        text-decoration: none;
        color: var(--fn-charcoal, #1f2937);
        transition: background 0.15s ease;
    }

    .fn-notification-item:last-child {
        border-bottom: none;
    }

    .fn-notification-item:hover {
        background: #fafafa;
    }

    .fn-notification-item.unread {
        background: rgba(255, 56, 92, 0.03);
    }

    .fn-notification-item.unread:hover {
        background: rgba(255, 56, 92, 0.06);
    }

    .fn-unread-indicator {
        flex-shrink: 0;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--fn-red, #ff385c);
        margin-top: 6px;
        box-shadow: 0 0 0 2px rgba(255, 56, 92, 0.1);
    }

    .fn-notification-content {
        flex: 1;
        min-width: 0;
    }

    .fn-notification-title-line {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 4px;
    }

    .fn-notification-item-title {
        margin: 0;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--fn-charcoal, #1f2937);
        line-height: 1.3;
    }

    .fn-notification-item.unread .fn-notification-item-title {
        font-weight: 700;
    }

    .fn-notification-item-time {
        font-size: 0.75rem;
        color: var(--fn-gray-dark, #9ca3af);
        white-space: nowrap;
        flex-shrink: 0;
    }

    .fn-notification-item-message {
        margin: 0;
        font-size: 0.85rem;
        color: var(--fn-gray-dark, #6b7280);
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .fn-notification-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 48px 24px;
        text-align: center;
        color: var(--fn-gray-dark, #9ca3af);
    }

    .fn-notification-empty svg {
        width: 48px;
        height: 48px;
        margin-bottom: 12px;
        opacity: 0.25;
    }

    .fn-notification-empty p {
        margin: 0;
        font-size: 0.9rem;
    }

    .fn-notification-footer {
        padding: 12px 16px;
        border-top: 1px solid #f0f0f0;
        flex-shrink: 0;
    }

    .fn-see-all-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: 100%;
        padding: 10px 12px;
        background: #f8f8f8;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        color: var(--fn-charcoal, #1f2937);
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.18s ease;
        cursor: pointer;
    }

    .fn-see-all-link:hover {
        background: #f0f0f0;
        border-color: var(--fn-gray-dark, #d1d5db);
        color: var(--fn-red, #ff385c);
    }

    .fn-see-all-link svg {
        width: 14px;
        height: 14px;
    }

    /* Scrollbar styling */
    .fn-notification-items::-webkit-scrollbar {
        width: 5px;
    }

    .fn-notification-items::-webkit-scrollbar-track {
        background: transparent;
    }

    .fn-notification-items::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 3px;
    }

    .fn-notification-items::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }

    /* Mobile responsiveness */
    @media (max-width: 520px) {
        .fn-notification-panel {
            right: -8px;
            width: calc(100vw - 16px);
            max-height: 70vh;
            max-width: 400px;
        }

        .fn-notification-title {
            font-size: 0.95rem;
        }

        .fn-notification-header {
            padding: 14px;
        }

        .fn-notification-item {
            padding: 12px 14px;
            gap: 10px;
        }

        .fn-header-action-btn {
            padding: 5px 8px;
            font-size: 0.75rem;
        }

        .fn-header-action-btn span {
            display: none;
        }

        .fn-notification-item-title {
            font-size: 0.85rem;
        }

        .fn-notification-item-message {
            font-size: 0.8rem;
        }
    }

    @media (max-width: 360px) {
        .fn-notification-panel {
            right: -4px;
            width: calc(100vw - 8px);
        }

        .fn-notification-header {
            padding: 12px;
        }

        .fn-notification-item {
            padding: 10px 12px;
        }
    }
</style>

<script>
    (() => {
        if (window.findNestNotificationOutsideCloseReady) {
            return;
        }

        window.findNestNotificationOutsideCloseReady = true;

        const closeOpenNotificationMenus = (event) => {
            document.querySelectorAll('.fn-notification-menu[open]').forEach((menu) => {
                if (!menu.contains(event.target)) {
                    menu.removeAttribute('open');
                }
            });
        };

        document.addEventListener('click', closeOpenNotificationMenus);
    })();
</script>
<?php /**PATH C:\xampp\htdocs\FindNest\resources\views/components/notification-dropdown.blade.php ENDPATH**/ ?>