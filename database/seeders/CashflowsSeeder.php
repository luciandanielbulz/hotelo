<?php

namespace Database\Seeders;

use App\Models\Cashflows;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CashflowsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cashflows::factory()->count(200)->create();
    }
}
