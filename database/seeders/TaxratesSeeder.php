<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxratesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Leere die Tabelle, um sicherzustellen, dass nur die gewünschten Datensätze eingefügt werden
        DB::table('taxrates')->delete();

        // Definiere die spezifischen Steuersätze
        $taxrates = [
            ['id' => 1, 'taxrate' => 0],  // 0% Steuer
            ['id' => 2, 'taxrate' => 20], // 20% Steuer
        ];

        // Füge die Datensätze ein oder aktualisiere sie
        foreach ($taxrates as $taxrate) {
            DB::table('taxrates')->updateOrInsert(['id' => $taxrate['id']], $taxrate);
        }
    }
}
