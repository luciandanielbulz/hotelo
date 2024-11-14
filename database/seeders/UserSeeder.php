<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'id' => 1,
            'username' => 'lucianbulz',
            'normalizedusername' => 'LUCIANBULZ',
            'password' => '$2y$12$OdEA0MGImEynuMkFbIZJFua7E6F57yEfuSAOIlLSnn9opmrLDozdu',
            'name' => 'Lucian',
            'lastname' => 'Bulz',
            'email' => 'lucian@bulz.at',
            'role_id' => 1,
            'isactive' => 1,
            'client_id' => 1
        ]);
    }

}
