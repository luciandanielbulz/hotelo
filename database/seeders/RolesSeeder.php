<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'admin',
            'description' => 'Administrator mit vollen Rechten',
        ]);

        Role::create([
            'name' => 'editor',
            'description' => 'Benutzer mit Rechten zum Bearbeiten von Inhalten',
        ]);
    }
}

