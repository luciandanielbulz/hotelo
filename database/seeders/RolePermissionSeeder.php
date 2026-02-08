<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Rollen erstellen oder existierende abrufen
        $adminRole = Role::firstOrCreate(['name' => 'Administrator'], ['description' => 'Administrator mit vollen Rechten']);
        $editorRole = Role::firstOrCreate(['name' => 'Superuser'], ['description' => 'Editor mit Bearbeitungsrechten']);

        // Hole ALLE Berechtigungen aus der Datenbank (die vom PermissionsSeeder erstellt wurden)
        $allPermissions = Permission::all();

        // Weise ALLE Berechtigungen der Administrator-Rolle zu
        foreach ($allPermissions as $permission) {
            $adminRole->permissions()->syncWithoutDetaching($permission->id);
        }

        // Verknüpfung mit Editor-Rolle (nur bestimmte Berechtigungen)
        $editorPermissions = [
            'view_dashboard',
            'view_customers',
            'view_offers',
            'view_invoices',
            'view_invoice_uploads',
            'view_sales_analysis',
            'edit_my_client_settings',
            'send_emails',
            'view_messages',
            'logout'
        ];

        foreach ($editorPermissions as $permissionName) {
            $permission = Permission::where('name', $permissionName)->first();
            if ($permission) {
                $editorRole->permissions()->syncWithoutDetaching($permission->id);
            }
        }
    }
}
