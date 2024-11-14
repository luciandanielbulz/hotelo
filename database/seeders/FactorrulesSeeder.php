<?php

namespace Database\Seeders;

use App\Models\Factorrules;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FactorrulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Factorrules::factory()->count(10)->create();
    }
}
