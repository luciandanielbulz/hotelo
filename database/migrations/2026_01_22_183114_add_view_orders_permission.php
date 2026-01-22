<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;
use App\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Prüfe ob die Berechtigung bereits existiert
        $exists = DB::table('permissions')->where('name', 'view_orders')->exists();
        
        if (!$exists) {
            // Füge die neue Berechtigung hinzu
            $data = [
                'name' => 'view_orders',
                'description' => 'Bestellungen anzeigen und verwalten',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            // Füge category nur hinzu, wenn die Spalte existiert
            if (Schema::hasColumn('permissions', 'category')) {
                $data['category'] = 'System & Konfiguration';
            }
            
            $permissionId = DB::table('permissions')->insertGetId($data);
            
            // Weise die Berechtigung allen Administrator-Rollen zu
            $adminRoles = Role::whereIn('name', ['Administrator', 'Admin', 'Superuser'])->get();
            
            foreach ($adminRoles as $role) {
                $exists = DB::table('role_permission')
                    ->where('role_id', $role->id)
                    ->where('permission_id', $permissionId)
                    ->exists();
                    
                if (!$exists) {
                    DB::table('role_permission')->insert([
                        'role_id' => $role->id,
                        'permission_id' => $permissionId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permission = DB::table('permissions')->where('name', 'view_orders')->first();
        
        if ($permission) {
            // Entferne die Berechtigung aus allen Rollen
            DB::table('role_permission')->where('permission_id', $permission->id)->delete();
            
            // Lösche die Berechtigung
            DB::table('permissions')->where('name', 'view_orders')->delete();
        }
    }
};
