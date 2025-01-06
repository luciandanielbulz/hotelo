<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(TaxratesSeeder::class);
        $this->call(ClientsSeeder::class);
        $this->call(ConditionsSeeder::class);
        $this->call(SalutationsSeeder::class);
        /*$this->call(CustomerSeeder::class);*/
        $this->call(UnitsSeeder::class);
        /*$this->call(OffersSeeder::class);
        $this->call(InvoicesSeeder::class);
        $this->call(OfferpositionsSeeder::class);
        $this->call(InvoicepositionsSeeder::class);*/
        /*$this->call(CashflowsSeeder::class);*/
        /*$this->call(MessagesSeeder::class);*/
        $this->call(RolesSeeder::class);
        $this->call(PermissionsSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(RolePermissionSeeder::class);

        /*$this->call(FactorrulesSeeder::class);*/
        /*$this->call(FileuploadsSeeder::class);*/



    }
}
