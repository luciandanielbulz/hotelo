<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Erstelle 100 Datensätze mit den Werten aus der Factory
        Customer::factory(100)->create();

    }
}
