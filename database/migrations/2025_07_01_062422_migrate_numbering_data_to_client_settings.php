<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migriere Daten von clients zu client_settings
        // Wir wollen nur die ursprünglichen Clients (parent_client_id IS NULL), nicht die Versionen
        
        $originalClients = DB::table('clients')
            ->whereNull('parent_client_id')
            ->get();

        foreach ($originalClients as $client) {
            DB::table('client_settings')->insert([
                'client_id' => $client->id,
                'lastinvoice' => $client->lastinvoice ?? 0,
                'lastoffer' => $client->lastoffer ?? 0,
                'invoicemultiplikator' => $client->invoicemultiplikator ?? 1,
                'offermultiplikator' => $client->offermultiplikator ?? 1,
                'invoice_number_format' => $client->invoice_number_format,
                'offer_number_format' => null, // Kann später gesetzt werden
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        echo "Migriert " . count($originalClients) . " Client-Einstellungen\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Lösche alle migrierten Daten
        DB::table('client_settings')->truncate();
        
        echo "Client-Einstellungen zurückgesetzt\n";
    }
};
