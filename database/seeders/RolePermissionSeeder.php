<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Roles;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Rollen erstellen oder existierende abrufen
        $adminRole = Roles::firstOrCreate(['name' => 'admin'], ['description' => 'Administrator mit vollen Rechten']);
        $editorRole = Roles::firstOrCreate(['name' => 'editor'], ['description' => 'Editor mit Bearbeitungsrechten']);

        // Berechtigungen erstellen oder existierende abrufen
        $permissions = [
            'view_dashboard',
            'edit_posts',
            'delete_posts',
        ];

        foreach ($permissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName]);

            // Verknüpfung mit Admin-Rolle
            $adminRole->permissions()->syncWithoutDetaching($permission->id);

            // Verknüpfung mit Editor-Rolle (nur bestimmte Berechtigungen)
            if ($permissionName === 'edit_posts') {
                $editorRole->permissions()->syncWithoutDetaching($permission->id);
            }
        }
    }
}
