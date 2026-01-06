<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        
        // Täglich Mahnwesen verarbeiten (um 2 Uhr morgens) - für alle Clients
        $schedule->call(function () {
            $clients = \App\Models\Clients::whereNull('parent_client_id')->get(); // Nur Haupt-Clients
            foreach ($clients as $client) {
                \App\Jobs\ProcessDunningInvoices::dispatch($client->id);
            }
        })->dailyAt('02:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
