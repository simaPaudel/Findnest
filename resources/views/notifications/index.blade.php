@php
    $user = auth()->user();
    $isAdmin = $user && $user->isAdmin();
    $isOwner = $user && $user->isOwner();
    $isRegularUser = $user && $user->isUser();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - FindNest</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* FindNest Theme */
        :root {
            --fn-red: #FF385C;
            --fn-red-hover: #E11D48;
            --fn-white: #FFFFFF;
            --fn-gray-light: #F3F4F6;
            --fn-gray-lighter: #F9FAFB;
            --fn-charcoal: #1F2937;
            --fn-gray-border: #E5E7EB;
            --fn-gray-dark: #6B7280;
            --fn-text-secondary: #9CA3AF;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            font-size: 16px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--fn-gray-lighter);
            color: var(--fn-charcoal);
            line-height: 1.6;
        }

        .notifications-page-wrapper {
            max-width: 900px;
            margin: 0 auto;
            padding: 24px 16px;
        }

        .notifications-page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }

        .notifications-page-title {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .notifications-page-title h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--fn-charcoal);
        }

        .notifications-page-title p {
            font-size: 14px;
            color: var(--fn-gray-dark);
        }

        .notifications-page-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-primary,
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .btn-primary {
            background: var(--fn-red);
            color: white;
        }

        .btn-primary:hover {
            background: var(--fn-red-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 56, 92, 0.2);
        }

        .btn-secondary {
            background: var(--fn-gray-light);
            color: var(--fn-charcoal);
            border: 1px solid var(--fn-gray-border);
        }

        .btn-secondary:hover {
            background: white;
            border-color: var(--fn-gray-dark);
        }

        .notifications-container {
            background: white;
            border-radius: 12px;
            border: 1px solid var(--fn-gray-border);
            overflow: hidden;
        }

        .notifications-empty {
            padding: 60px 24px;
            text-align: center;
        }

        .notifications-empty-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 16px;
            opacity: 0.3;
        }

        .notifications-empty-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--fn-charcoal);
            margin-bottom: 8px;
        }

        .notifications-empty-description {
            font-size: 14px;
            color: var(--fn-gray-dark);
            max-width: 300px;
            margin: 0 auto;
        }

        .notifications-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .notification-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px;
            border-bottom: 1px solid #F3F4F6;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s ease;
            background: white;
        }

        .notification-item:hover {
            background: #FAFBFC;
        }

        .notification-item.unread {
            background: rgba(255, 56, 92, 0.04);
        }

        .notification-item.unread:hover {
            background: rgba(255, 56, 92, 0.08);
        }

        .notification-item.unread .notification-content-title {
            font-weight: 600;
        }

        .notification-status-indicator {
            flex-shrink: 0;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: transparent;
            margin-top: 5px;
        }

        .notification-item.unread .notification-status-indicator {
            background: var(--fn-red);
        }

        .notification-content {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .notification-content-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
        }

        .notification-content-title {
            font-size: 15px;
            font-weight: 500;
            color: var(--fn-charcoal);
        }

        .notification-content-time {
            font-size: 13px;
            color: var(--fn-gray-dark);
            white-space: nowrap;
            flex-shrink: 0;
        }

        .notification-content-message {
            font-size: 14px;
            color: var(--fn-gray-dark);
            line-height: 1.5;
            margin-top: 2px;
        }

        .notification-content-type {
            display: inline-block;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 3px 8px;
            border-radius: 4px;
            background: #F0F1F2;
            color: var(--fn-gray-dark);
            margin-top: 6px;
            width: fit-content;
        }

        .notification-item.unread .notification-content-type {
            background: rgba(255, 56, 92, 0.12);
            color: var(--fn-red);
        }

        /* Pagination */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 24px;
            flex-wrap: wrap;
        }

        .pagination a,
        .pagination span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0 8px;
            border-radius: 6px;
            font-size: 14px;
            text-decoration: none;
            border: 1px solid var(--fn-gray-border);
            background: white;
            color: var(--fn-charcoal);
            transition: all 0.2s ease;
        }

        .pagination a:hover {
            background: var(--fn-gray-light);
            border-color: var(--fn-gray-dark);
        }

        .pagination span.active {
            background: var(--fn-red);
            border-color: var(--fn-red);
            color: white;
        }

        /* Alerts */
        .alert {
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #D1FAE5;
            border: 1px solid #6EE7B7;
            color: #065F46;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .notifications-page-wrapper {
                padding: 16px 12px;
            }

            .notifications-page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .notifications-page-actions {
                width: 100%;
            }

            .notifications-page-actions button,
            .notifications-page-actions a {
                flex: 1;
                justify-content: center;
                min-width: auto;
            }

            .notification-item {
                padding: 14px 12px;
                gap: 10px;
            }

            .notification-content-header {
                flex-direction: column;
            }

            .notification-content-time {
                width: 100%;
                text-align: left;
            }

            .notifications-page-title h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="notifications-page-wrapper">
        <div class="notifications-page-header">
            <div class="notifications-page-title">
                <h1>Notifications</h1>
                <p>{{ $unreadCount }} unread {{ $unreadCount === 1 ? 'notification' : 'notifications' }}</p>
            </div>
            <div class="notifications-page-actions">
                @if($unreadCount > 0)
                    <form method="POST" action="{{ route('notifications.mark-all-read') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-secondary">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Mark All as Read
                        </button>
                    </form>
                @endif
                <a href="@if($isAdmin){{ route('admin.dashboard') }}@elseif($isOwner){{ route('owner.dashboard') }}@else{{ route('listings.index') }}@endif" class="btn-secondary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="notifications-container">
            @if($notifications->isEmpty())
                <div class="notifications-empty">
                    <svg class="notifications-empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0m6 0H9"></path>
                    </svg>
                    <div class="notifications-empty-title">No notifications</div>
                    <p class="notifications-empty-description">You're all caught up! Check back later for new updates.</p>
                </div>
            @else
                <ul class="notifications-list">
                    @foreach($notifications as $notification)
                        <li class="notification-item {{ !$notification->is_read ? 'unread' : '' }}" onclick="window.location.href='{{ route('notifications.open', $notification) }}';" style="cursor: pointer;">
                            <div class="notification-status-indicator"></div>
                            <div class="notification-content" style="flex: 1;">
                                <div class="notification-content-header">
                                    <div class="notification-content-title">{{ $notification->title }}</div>
                                    <div class="notification-content-time">{{ optional($notification->created_at)->diffForHumans() }}</div>
                                </div>
                                <p class="notification-content-message">{{ $notification->message }}</p>
                                <div class="notification-content-type">{{ ucfirst($notification->type) }}</div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        @if($notifications->hasPages())
            <div class="pagination-wrapper">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</body>
</html>
