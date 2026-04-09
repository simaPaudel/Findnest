<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
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
