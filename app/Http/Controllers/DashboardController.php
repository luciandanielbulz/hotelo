<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user(); // Angemeldeter Benutzer
        $roleId = $user->role_id; // Rolle des Benutzers

        // Lade Berechtigungen des Benutzers aus der Datenbank
        $permissions = \DB::table('permissions')
            ->join('role_permission', 'permissions.id', '=', 'role_permission.permission_id')
            ->where('role_permission.role_id', $roleId)
            ->pluck('permissions.name')
            ->toArray();

        $tiles = [];

        $this->addTileIfPermitted($tiles, $permissions, "view_customers", "/customer", "fa-solid fa-people-group", "Kunden", "#e0ebfc", "Kundenliste", 1);
        $this->addTileIfPermitted($tiles, $permissions, "view_offers", "/offer", "fa-regular fa-envelope", "Meine Angebote", "#e1ebfc", "Angeboteliste...", 3);
        $this->addTileIfPermitted($tiles, $permissions, "view_invoices", "/invoice", "fa-solid fa-file-invoice", "Meine Rechnungen", "#fce8e8", "Liste der Zeitnachweise...", 5);
        $this->addTileIfPermitted($tiles, $permissions, "view_sales_analysis", "/sales", "fa-solid fa-magnifying-glass-chart", "Umsatzauswertung", "#fce8e8", "Liste der Umsätze...", 6);
        $this->addTileIfPermitted($tiles, $permissions, "view_email_list", "/emaillist", "fa-solid fa-envelope-open-text", "E-Mail Liste", "#f8f9fa", "Hier werden die gesendeten Objekte angezeigt", 7);
        $this->addTileIfPermitted($tiles, $permissions, "manage_users", "/users", "fa-solid fa-user-gear", "Benutzerverwaltung", "#f0fcfc", "Benutzer werden hier bearbeitet", 9);
        $this->addTileIfPermitted($tiles, $permissions, "manage_roles", "/roles", "fa-regular fa-circle-user", "Rollenverwaltung", "#f8f9fa", "Rollen werden hier festgelegt", 11);
        $this->addTileIfPermitted($tiles, $permissions, "view_clients", "/clients", "fa-regular fa-circle-user", "Klientenverwaltung", "#f8f9af", "Klienten werden hier festgelegt", 12);
        $this->addTileIfPermitted($tiles, $permissions, "update_settings", "/settings", "fa-solid fa-gear", "Einstellungen", "#ecfce8", "Einstellungen werden hier festgelegt", 13);
        $this->addTileIfPermitted($tiles, $permissions, "edit_personal_settings", "/personal_settings/edit", "fa-solid fa-person-burst", "Eigene Einstellungen", "#fffce8", "Eigene Einstellungen ändern", 15);
        $this->addTileIfPermitted($tiles, $permissions, "logout", "/logout", "fa-solid fa-arrow-right-from-bracket", "Ausloggen", "#f9fce8", "Aus Programm aussteigen", 17);

        // Sortiere Kacheln nach Priorität
        usort($tiles, function ($a, $b) {
            return $a['priority'] <=> $b['priority'];
        });

        return view('dashboard.index', compact('tiles'));
    }

    private function addTileIfPermitted(&$tiles, $permissions, $requiredPermission, $targetFile, $icon, $title, $backgroundColor, $text, $priority)
    {
        if (in_array($requiredPermission, $permissions)) {
            $this->addTile($tiles, $targetFile, $icon, $title, $backgroundColor, $text, $priority);
        }
    }

    private function addTile(&$tiles, $targetFile, $icon, $title, $backgroundColor, $text, $priority)
    {
        $tiles[] = [
            'targetFile' => $targetFile,
            'icon' => $icon,
            'title' => $title,
            'backgroundColor' => $backgroundColor,
            'text' => $text,
            'priority' => $priority,
        ];
    }

    // ... other methods ...
}
