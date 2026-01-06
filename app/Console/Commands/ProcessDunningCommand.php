<?php

namespace App\Console\Commands;

use App\Jobs\ProcessDunningInvoices;
use Illuminate\Console\Command;

class ProcessDunningCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dunning:process {--client_id= : Spezifische Client-ID (optional, sonst alle Clients)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verarbeitet alle Rechnungen und berechnet die Mahnstufen';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $clientId = $this->option('client_id');
        
        if ($clientId) {
            // Nur für spezifischen Client
            $this->info("Starte Mahnwesen-Verarbeitung für Client-ID: {$clientId}...");
            $job = new ProcessDunningInvoices($clientId);
            $job->handle();
            $this->info('Mahnwesen-Verarbeitung abgeschlossen!');
        } else {
            // Für alle Clients
            $clients = \App\Models\Clients::whereNull('parent_client_id')->get();
            $this->info("Starte Mahnwesen-Verarbeitung für {$clients->count()} Client(s)...");
            
            foreach ($clients as $client) {
                $this->info("Verarbeite Client-ID: {$client->id}...");
                $job = new ProcessDunningInvoices($client->id);
                $job->handle();
            }
            
            $this->info('Mahnwesen-Verarbeitung für alle Clients abgeschlossen!');
        }
        
        return Command::SUCCESS;
    }
}
