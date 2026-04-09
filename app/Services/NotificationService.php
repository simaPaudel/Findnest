<?php

namespace App\Services;

use App\Models\AppNotification;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class NotificationService
{
    /**
     * Send a notification to a user by ID.
     *
     * @param int $userId
     * @param string $type
     * @param string $title
     * @param string $message
     * @param string|null $actionUrl
     * @return AppNotification
     */
    public static function sendNotification(int $userId, string $type, string $title, string $message, ?string $actionUrl = null): AppNotification
    {
        return AppNotification::query()->create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'action_url' => self::normalizeActionUrl($actionUrl),
            'is_read' => 0,
        ]);
    }

    /**
     * Create a notification for a user model.
     *
     * Kept for backward compatibility with existing callers.
     */
    public static function create(User $user, string $type, string $title, string $message, ?string $actionUrl = null): AppNotification
    {
        return self::sendNotification($user->id, $type, $title, $message, $actionUrl);
    }

    /**
     * Create a booking notification
     */
    public static function notifyBooking(User $user, string $message, string $propertyTitle, ?string $bookingId = null): AppNotification
    {
        $actionUrl = $bookingId ? route('user.bookings.index') : null;
        
        return self::create(
            $user,
            'booking',
            "Booking Update: {$propertyTitle}",
            $message,
            $actionUrl
        );
    }

    /**
     * Create a payment notification
     */
    public static function notifyPayment(User $user, string $message, ?string $bookingId = null): AppNotification
    {
        $actionUrl = $bookingId ? route('user.bookings.index') : null;
        
        return self::create(
            $user,
            'payment',
            'Payment Required',
            $message,
            $actionUrl
        );
    }

    /**
     * Create a property notification
     */
    public static function notifyProperty(User $user, string $message, string $propertyTitle): AppNotification
    {
        return self::create(
            $user,
            'property',
            "Property Update: {$propertyTitle}",
            $message,
            route('listings.index')
        );
    }

    /**
     * Get unread notification count for a user
     */
    public static function getUnreadCount(User $user): int
    {
        return self::countUnreadNotifications($user->id);
    }

    /**
     * Get recent notifications for a user
     */
    public static function getRecent(User $user, int $limit = 5): EloquentCollection
    {
        return self::fetchNotifications($user->id, $limit);
    }

    /**
     * Fetch notifications for a user by ID, latest first.
     */
    public static function fetchNotifications(int $userId, int $limit = 10): EloquentCollection
    {
        return AppNotification::query()
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Count unread notifications for a user by ID.
     */
    public static function countUnreadNotifications(int $userId): int
    {
        return AppNotification::query()
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->count();
    }

    /**
     * Mark all notifications as read for a user by ID.
     */
    public static function markNotificationsAsRead(int $userId): int
    {
        return AppNotification::query()
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->update(['is_read' => 1]);
    }

    /**
     * Mark a single notification as read for a specific user.
     */
    public static function markNotificationAsRead(int $notificationId, int $userId): bool
    {
        return AppNotification::query()
            ->where('id', $notificationId)
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->update(['is_read' => 1]) > 0;
    }

    /**
     * Mark all notifications as read for a user
     */
    public static function markAllAsRead(User $user): void
    {
        self::markNotificationsAsRead($user->id);
    }

    /**
     * Normalize an action URL so empty strings become null.
     */
    private static function normalizeActionUrl(?string $actionUrl): ?string
    {
        $actionUrl = trim((string) $actionUrl);

        return $actionUrl === '' ? null : $actionUrl;
    }
}
