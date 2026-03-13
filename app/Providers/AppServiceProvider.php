<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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
    }
}
