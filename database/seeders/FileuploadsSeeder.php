<?php

namespace Database\Seeders;

use App\Models\Fileuploads;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FileuploadsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Fileuploads::factory()->count(50)->create();
    }
}
