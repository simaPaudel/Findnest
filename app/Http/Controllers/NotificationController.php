<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Display all notifications for the authenticated user.
     */
    public function index(Request $request): View
    {
        abort_unless(Auth::check(), 403);

        $userId = (int) Auth::id();
        $perPage = $request->query('per_page', 20);

        $notifications = AppNotification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $unreadCount = AppNotification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();

        return view('notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Mark all notifications as read for the authenticated user.
     */
    public function markAllAsRead(Request $request): RedirectResponse
    {
        abort_unless(Auth::check(), 403);

        $userId = (int) Auth::id();

        AppNotification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $redirect = $request->query('redirect_to', 'notifications.index');

        return redirect()
            ->back()
            ->with('success', 'All notifications marked as read.');
    }

    /**
     * Open a notification, mark it as read, then redirect to its target.
     */
    public function open(AppNotification $notification): RedirectResponse
    {
        abort_unless(Auth::check(), 403);
        abort_unless((int) $notification->user_id === (int) Auth::id(), 403);

        NotificationService::markNotificationAsRead($notification->id, (int) Auth::id());

        if (!empty($notification->action_url)) {
            return redirect()->to($notification->action_url);
        }

        return redirect()->back();
    }
}
