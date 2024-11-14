<?php

namespace Database\Seeders;

use App\Models\Messages;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Messages::factory()->count(100)->create();
    }
}
