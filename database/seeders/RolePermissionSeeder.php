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

        // Berechtigungen erstellen oder existierende abrufen
        $permissions = [
            'view_dashboard',
            'edit_posts',
            'delete_posts',
            'create_users',
            'edit_users',
            'delete_users',
            'view_customers',
            'view_offers',
            'view_invoices',
            'view_invoice_uploads',
            'view_sales_analysis',
            'view_email_list',
            'manage_users',
            'manage_roles',
            'view_clients',
            'update_settings',
            'logout',
            'manage_permissions',
            'edit_my_client_settings'
        ];

        foreach ($permissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName]);

            // Verknüpfung mit Admin-Rolle
            $adminRole->permissions()->syncWithoutDetaching($permission->id);

            // Verknüpfung mit Editor-Rolle (nur bestimmte Berechtigungen)
            if (in_array($permissionName, ['edit_posts', 'view_dashboard', 'view_customers', 'view_offers', 'view_invoices', 'view_invoice_uploads', 'view_sales_analysis', 'edit_my_client_settings'])) {
                $editorRole->permissions()->syncWithoutDetaching($permission->id);
            }
        }
    }
}
