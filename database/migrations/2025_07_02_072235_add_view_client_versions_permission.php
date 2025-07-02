<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Neue Permission hinzufügen
        Permission::create([
            'name' => 'view_client_versions',
            'description' => 'Kann Versionshistorie von Client-Daten einsehen'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Permission entfernen
        Permission::where('name', 'view_client_versions')->delete();
    }
};
