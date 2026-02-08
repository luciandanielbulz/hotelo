<?php

namespace App\Modules\Booking;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class BookingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register module services if needed
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load module routes
        $this->loadRoutes();
        
        // Load module migrations
        $this->loadMigrationsFrom(__DIR__ . '/Migrations');
        
        // Load module views
        $this->loadViewsFrom(__DIR__ . '/Resources/views', 'booking');
        
        // Publish module assets if needed
        $this->publishes([
            __DIR__ . '/Resources/views' => resource_path('views/vendor/booking'),
        ], 'booking-views');
    }

    /**
     * Load module routes
     */
    protected function loadRoutes(): void
    {
        Route::middleware('web')
            ->group(__DIR__ . '/routes.php');
    }
}
