<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServerMonitoringPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Prüfe, ob die Berechtigung bereits existiert
        $permissionId = DB::table('permissions')->where('name', 'view_server_monitoring')->first();
        
        if (!$permissionId) {
            // Füge die Server-Monitoring-Berechtigung hinzu
            $permissionId = DB::table('permissions')->insertGetId([
                'name' => 'view_server_monitoring',
                'description' => 'Server-Monitoring anzeigen',
                'category' => 'Administration',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $permissionId = $permissionId->id;
        }

        // Finde alle Admin-Rollen und füge die Berechtigung hinzu
        $adminRoles = DB::table('roles')->where('name', 'like', '%admin%')->orWhere('name', 'like', '%Admin%')->get();
        
        foreach ($adminRoles as $role) {
            DB::table('role_permission')->insert([
                'role_id' => $role->id,
                'permission_id' => $permissionId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Optional: Füge die Berechtigung auch zu anderen relevanten Rollen hinzu
        $systemRoles = DB::table('roles')->where('name', 'like', '%system%')->orWhere('name', 'like', '%System%')->get();
        
        foreach ($systemRoles as $role) {
            // Prüfe, ob die Berechtigung bereits existiert
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