<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermissions;

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
            'description' => 'Währungen verwalten (erstellen, bearbeiten, löschen)',
        ]);

        // Finde alle Admin/SuperAdmin Rollen und weise die Berechtigung zu
        $adminRoles = Role::whereIn('name', ['Admin', 'SuperAdmin', 'admin', 'superadmin'])->get();
        
        foreach ($adminRoles as $role) {
            // Prüfe ob die Berechtigung bereits zugewiesen ist
            $exists = RolePermissions::where('role_id', $role->id)
                ->where('permission_id', $permission->id)
                ->exists();
                
            if (!$exists) {
                RolePermissions::create([
                    'role_id' => $role->id,
                    'permission_id' => $permission->id,
                ]);
                
                $this->command->info("Berechtigung 'manage_currencies' zu Rolle '{$role->name}' hinzugefügt.");
            } else {
                $this->command->info("Berechtigung 'manage_currencies' bereits in Rolle '{$role->name}' vorhanden.");
            }
        }

        $this->command->info('Currency permission seeder completed successfully.');
    }
}
