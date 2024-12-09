<?php

namespace App\Providers;
use Illuminate\Support\Facades\Blade;

use Illuminate\Support\ServiceProvider;
use App\View\Components\Button;
use App\View\Components\Input;

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
        Blade::component('components.dropdown', 'dropdown');
        Blade::component('button', Button::class);
        Blade::component('input', Input::class);
    }
}
