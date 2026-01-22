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
            // Dashboard & Navigation
            'Dashboard & Navigation' => [
                'view_dashboard' => 'Erlaubt Zugriff auf das Dashboard',
                'logout' => 'Ausloggen',
            ],

            // Kundenverwaltung
            'Kundenverwaltung' => [
                'view_customers' => 'Kunden sehen',
            ],

            // Geschäftsprozesse
            'Geschäftsprozesse' => [
                'view_offers' => 'Angebote sehen',
                'view_invoices' => 'Rechnungen sehen',
                'send_emails' => 'E-Mails versenden (Angebote und Rechnungen)',
                'use_translation' => 'Textübersetzung mit GPT verwenden',
            ],

            // Kommunikation
            'Kommunikation' => [
                'view_messages' => 'Postausgang für gesendete Mails sehen',
                // send_emails ersetzt view_email_list als aktives Recht zum Senden/Sehen
                'send_emails' => 'E-Mails versenden und senden-bezogene Ansichten sehen',
            ],

            // Analysen & Berichte
            'Analysen & Berichte' => [
                'view_sales_analysis' => 'Analyse des Umsatzes sehen',
                'view_dunning' => 'Mahnwesen anzeigen',
                'process_dunning' => 'Mahnwesen-Verarbeitung manuell starten',
            ],

            // Benutzerverwaltung
            'Benutzerverwaltung' => [
                'manage_users' => 'Benutzer verwalten',
                'create_users' => 'Erlaubt das Erstellen neuer Benutzer',
                'edit_users' => 'Erlaubt das Bearbeiten von Benutzerdaten',
                'delete_users' => 'Erlaubt das Löschen von Benutzern',
                'reset_user_password' => 'Benutzerpasswort zurücksetzen',
            ],

            // Rollen & Berechtigungen
            'Rollen & Berechtigungen' => [
                'manage_roles' => 'Rollen verwalten',
                'manage_permissions' => 'Rechte bearbeiten',
            ],

            // Firmen-Verwaltung
            'Firmen-Verwaltung' => [
                'view_clients' => 'Klienten verwalten',
                'edit_my_client_settings' => 'Eigene Firmen-Einstellungen bearbeiten',
                'view_client_versions' => 'Kann Versionshistorie von Client-Daten einsehen',
            ],

            // System & Konfiguration
            'System & Konfiguration' => [
                'update_settings' => 'Einstellungen sehen',
                'view_conditions' => 'Konditionen bearbeiten',
                'manage_maintenance' => 'Wartungsmodus verwalten',
                'manage_currencies' => 'Währungen verwalten',
                'manage_categories' => 'Kategorien verwalten',
                'edit_client_settings' => 'Client-Einstellungen bearbeiten',
                'manage_all_clients' => 'Alle Clients verwalten',
                'view_system_info' => 'System-Informationen und Versionsdaten anzeigen',
                'view_orders' => 'Bestellungen anzeigen und verwalten',
            ],

            
        ];

        foreach ($permissions as $category => $categoryPermissions) {
            foreach ($categoryPermissions as $name => $description) {
                Permission::updateOrCreate(
                    ['name' => $name], // Einzigartige Spalte
                    [
                        'description' => $description,
                        'category' => $category
                    ] // Werte zum Aktualisieren/Erstellen
                );
            }
        }
    }
}
