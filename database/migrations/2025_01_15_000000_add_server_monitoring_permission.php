<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Füge die neue Berechtigung hinzu
        DB::table('permissions')->insert([
            'name' => 'view_server_monitoring',
            'description' => 'Server-Monitoring anzeigen',
            'category' => 'Administration',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('permissions')->where('name', 'view_server_monitoring')->delete();
    }
}; 