<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalutationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $salutations = [
        ['id' => 1, 'name' => 'Herr'],
        ['id' => 2, 'name' => 'Frau'],
        ['id' => 3, 'name' => 'Firma'],
    ];

    foreach ($salutations as $salutation) {
        DB::table('salutations')->updateOrInsert(['id' => $salutation['id']], $salutation);
    }
}
}
