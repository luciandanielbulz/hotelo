<?php

namespace Database\Seeders;

use App\Models\Invoices;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Invoices::factory()->count(10)->create();
    }
}
