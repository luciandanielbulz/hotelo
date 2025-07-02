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
            'view_customers' => 'Kunden sehen',
            'view_offers' => 'Angebote sehen',
            'view_invoices' => 'Rechnungen sehen',
            'view_sales_analysis'=> 'Analyse des Umsatzes sehen',
            'view_email_list'=>'Liste der gesendeten E-Mails sehen',
            'manage_users'=>'Benutzer verwalten',
            'manage_roles'=>'Rollen verwalten',
            'view_clients' => 'Klienten verwalten',
            'update_settings'=> 'Einstellungen sehen',
            'logout'=>'Ausloggen',
            'manage_permissions'=>'Rechte bearbeiten',
            'view_messages' => "Postausgang für gesendete Mails sehen",
            'reset_user_password' => 'Benutzerpasswort zurücksetzen',
            'view_conditions' => 'Konditionen bearbeiten',
            'edit_my_client_settings' => 'Eigene Firmen-Einstellungen bearbeiten',
            'view_client_versions' => 'Kann Versionshistorie von Client-Daten einsehen',
            'manage_maintenance' => 'Wartungsmodus verwalten'
        ];

        foreach ($permissions as $name => $description) {
            Permission::updateOrCreate(
                ['name' => $name], // Einzigartige Spalte
                ['description' => $description] // Werte zum Aktualisieren/Erstellen
            );
        }
    }
}
