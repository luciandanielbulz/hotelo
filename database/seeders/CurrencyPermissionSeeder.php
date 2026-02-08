<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;
use App\Models\Role;

class CurrencyPermissionSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Erstelle die Berechtigung für Währungsverwaltung
        $permission = Permission::firstOrCreate([
            'name' => 'manage_currencies',
        ], [
            'description' => 'Währungen verwalten (erstellen, bearbeiten, löschen)',
            'category' => 'System & Konfiguration',
        ]);

        // Finde alle Admin/SuperAdmin Rollen und weise die Berechtigung zu
        $adminRoles = Role::whereIn('name', ['Admin', 'SuperAdmin', 'admin', 'superadmin', 'Administrator', 'Superuser'])->get();
        
        foreach ($adminRoles as $role) {
            // Prüfe ob die Berechtigung bereits zugewiesen ist
            $exists = DB::table('role_permission')
                ->where('role_id', $role->id)
                ->where('permission_id', $permission->id)
                ->exists();
                
            if (!$exists) {
                DB::table('role_permission')->insert([
                    'role_id' => $role->id,
                    'permission_id' => $permission->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $this->command->info("Berechtigung 'manage_currencies' zu Rolle '{$role->name}' hinzugefügt.");
            } else {
                $this->command->info("Berechtigung 'manage_currencies' bereits in Rolle '{$role->name}' vorhanden.");
            }
        }

        $this->command->info('Currency permission seeder completed successfully.');
    }
}
