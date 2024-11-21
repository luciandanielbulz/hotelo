<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $permissions = [
            'view_dashboard' => 'Erlaubt Zugriff auf das Dashboard',
            'edit_posts' => 'Erlaubt das Bearbeiten von Beiträgen',
            'delete_posts' => 'Erlaubt das Löschen von Beiträgen',
            'create_users' => 'Erlaubt das Erstellen neuer Benutzer',
            'edit_users' => 'Erlaubt das Bearbeiten von Benutzerdaten',
            'delete_users' => 'Erlaubt das Löschen von Benutzern',
        ];

        foreach ($permissions as $name => $description) {
            Permission::firstOrCreate(
                ['name' => $name], // Einzigartige Spalte
                ['description' => $description] // Standardwert bei Erstellung
            );
        }
    }
}
