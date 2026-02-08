<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Clients;

class ClientsSeeder extends Seeder
{
    /**
     * Seed the application's database with a specific client.
     */
    public function run()
    {
        // Verhindert doppelte Einträge
        if (!Clients::where('email', 'office@bulz.at')->exists()) {
            $client = Clients::create([
                'clientname' => 'Lucian-Daniel Bulz',
                'companyname' => 'Ing. Lucian-Daniel Bulz',
                'business' => 'Kleinunternehmen',
                'address' => 'Neue-Welt-Gasse 3',
                'postalcode' => 8600,
                'location' => 'Bruck an der Mur',
                'email' => 'office@bulz.at',
                'phone' => '0664 35 67 645',
                'tax_id' => 1,
                'webpage' => 'www.bulz.at',
                'bank' => 'Sparkasse',
                'accountnumber' => 'AT81 0000 0000 0000 0000',
                'bic' => 'STSPAT3GXXX',
                'smallbusiness' => 1,
                'logo' => 'assets/logo.png',
                'logoheight' => 100,
                'logowidth' => 100,
                'signature' => "MFG\nBulz Lucian",
                'style' => 1,
            ]);

            // Erstelle die zugehörigen Client-Settings mit den Nummerierungsfeldern
            if ($client && Schema::hasTable('client_settings')) {
                DB::table('client_settings')->updateOrInsert(
                    ['client_id' => $client->id],
                    [
                        'lastoffer' => 1,
                        'lastinvoice' => 1,
                        'offermultiplikator' => 10000,
                        'invoicemultiplikator' => 10000,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
