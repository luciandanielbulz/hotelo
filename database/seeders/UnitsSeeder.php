<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Leere die Tabelle, um sicherzustellen, dass nur die gewünschten Datensätze eingefügt werden
        DB::table('units')->delete();

        // Feste Datensätze definieren
        $units = [
            ['id' => 1, 'unitdesignation' => 'Stk', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'unitdesignation' => 'Pau', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'unitdesignation' => 'Std', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'unitdesignation' => '.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'unitdesignation' => 'h', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'unitdesignation' => 'm', 'created_at' => now(), 'updated_at' => now()],
        ];

        // Füge die Datensätze ein oder aktualisiere sie
        foreach ($units as $unit) {
            DB::table('units')->updateOrInsert(['id' => $unit['id']], $unit);
        }
    }
}
