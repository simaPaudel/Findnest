<?php

namespace App\Providers;

use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register NPR currency formatting helper
        Blade::directive('npr', function ($expression) {
            return "<?php echo 'Rs ' . number_format((float)($expression), 0); ?>";
        });

        View::composer(['components.navbar', 'owner.layout', 'admin.layout', 'user.layout'], function ($view) {
            try {
                if (!Auth::check()) {
                    $view->with([
                        'notificationCount' => 0,
                        'recentNotifications' => collect(),
                    ]);

                    return;
                }

                $userId = (int) Auth::id();

                $view->with([
                    'notificationCount' => NotificationService::countUnreadNotifications($userId),
                    'recentNotifications' => NotificationService::fetchNotifications($userId, 10),
                ]);
            } catch (\Throwable $e) {
                $view->with([
                    'notificationCount' => 0,
                    'recentNotifications' => collect(),
                ]);
            }
        });
    }
}
