<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use App\Models\Clients;

class CreateIncomeCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'categories:create-income';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create income categories for all clients';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating income categories for all clients...');
        
        $clients = Clients::all();
        
        foreach ($clients as $client) {
            $this->info("Processing client: {$client->name} (ID: {$client->id})");
            
            // Prüfe ob bereits Einnahmen-Kategorien existieren
            $existingIncomeCategories = Category::where('client_id', $client->id)
                ->where('type', 'income')
                ->count();
                
            if ($existingIncomeCategories > 0) {
                $this->warn("Client {$client->name} already has {$existingIncomeCategories} income categories");
                continue;
            }
            
            // Erstelle Standard-Einnahmen-Kategorien
            $incomeCategories = [
                'Gehalt' => '#10B981',
                'Zinsen' => '#059669',
                'Verkauf' => '#047857',
                'Mieteinnahmen' => '#065F46',
                'Dividenden' => '#064E3B'
            ];
            
            foreach ($incomeCategories as $name => $color) {
                Category::create([
                    'name' => $name,
                    'color' => $color,
                    'type' => 'income',
                    'client_id' => $client->id,
                    'is_active' => true,
                    'billing_duration_years' => 0,
                    'percentage' => 0
                ]);
                
                $this->info("Created: {$name}");
            }
        }
        
        $this->info('Income categories created successfully!');
        
        return 0;
    }
} 