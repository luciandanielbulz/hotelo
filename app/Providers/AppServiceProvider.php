<?php

namespace App\Providers;
use Illuminate\Support\Facades\Blade;

use Illuminate\Support\ServiceProvider;
use App\View\Components\Button;
use App\View\Components\Input;
use Carbon\Carbon;



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
        Carbon::setLocale('de');
        setlocale(LC_TIME, 'de_DE.UTF-8');
        Blade::component('components.dropdown', 'dropdown');
        Blade::component('button', Button::class);
        Blade::component('input', Input::class);
    }
}
