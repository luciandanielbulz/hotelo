<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Prüfe ob die Berechtigung bereits existiert
        $exists = DB::table('permissions')->where('name', 'view_server_monitoring')->exists();
        
        if (!$exists) {
            // Füge die neue Berechtigung hinzu
            $data = [
                'name' => 'view_server_monitoring',
                'description' => 'Server-Monitoring anzeigen',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            // Füge category nur hinzu, wenn die Spalte existiert
            if (Schema::hasColumn('permissions', 'category')) {
                $data['category'] = 'Administration';
            }
            
            DB::table('permissions')->insert($data);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('permissions')->where('name', 'view_server_monitoring')->delete();
    }
}; 