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
        
        // Setze den App-Namen basierend auf der Domain
        $this->setAppNameByDomain();
    }
    
    /**
     * Setzt den App-Namen basierend auf der aktuellen Domain
     */
    protected function setAppNameByDomain(): void
    {
        // Nur ausführen, wenn eine Request-Instanz verfügbar ist (nicht im Console-Modus)
        if (!app()->runningInConsole()) {
            try {
                $host = request()->getHost();
                $domainNames = config('app.domain_names', []);
                
                if (isset($domainNames[$host])) {
                    config(['app.name' => $domainNames[$host]]);
                }
            } catch (\Exception $e) {
                // Ignoriere Fehler, wenn request() nicht verfügbar ist
            }
        }
    }
}
