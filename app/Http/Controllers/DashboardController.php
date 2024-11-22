<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    //$userid = auth()->user()->id;
    $userid = 1;

    $tiles = [];
    $this->addTile($tiles, "CustomerList", "/customer", "fa-solid fa-people-group", "Kunden", "#e0ebfc", "Kundenliste", 1);
    $this->addTile($tiles, "OfferList", "/offer", "fa-regular fa-envelope", "Meine Angebote", "#e1ebfc", "Angeboteliste...", 3);
    $this->addTile($tiles, "InvoicesList", "/invoice", "fa-solid fa-file-invoice", "Meine Rechnungen", "#fce8e8", "Liste der Zeitnachweise...", 5);
    $this->addTile($tiles, "SalesAnalysis", "/sales", "fa-solid fa-magnifying-glass-chart", "Umsatzauswertung", "#fce8e8", "Liste der Umsätze...", 6);
    $this->addTile($tiles, "EmailList", "/emaillist", "fa-solid fa-envelope-open-text", "E-Mail Liste", "#f8f9fa", "Hier werden die gesendeten Objekte angezeigt", 7);
    $this->addTile($tiles, "UserAdministration", "/users", "fa-solid fa-user-gear", "Benutzerverwaltung", "#f0fcfc", "Benutzer werden hier bearbeitet", 9);
    $this->addTile($tiles, "RolesAdministration", "/roles", "fa-regular fa-circle-user", "Rollenverwaltung", "#f8f9fa", "Rollen werden hier festgelegt", 11);
    $this->addTile($tiles, "Clients", "/clients", "fa-regular fa-circle-user", "Klientenverwaltung", "#f8f9af", "Klienten werden hier festgelegt", 12);
    $this->addTile($tiles, "Settings", "/settings", "fa-solid fa-gear", "Einstellungen", "#ecfce8", "Einstellungen werden hier festgelegt", 13);
    $this->addTile($tiles, "X", "/personal_settings/edit", "fa-solid fa-person-burst", "Eigene Einstellungen", "#fffce8", "Eigene Einstellungen ändern", 15);
    $this->addTile($tiles, "X", "/logout", "fa-solid fa-arrow-right-from-bracket", "Ausloggen", "#f9fce8", "Aus Programm aussteigen", 17);

    //dd($tiles);

    usort($tiles, function ($a, $b) {
        return $a['priority'] <=> $b['priority'];
    });

    return view('dashboard.index', compact('tiles'));
}

    private function addTile(&$tiles, string $userRole, string $targetFile, string $icon, string $title, string $backgroundColor, string $text, int $priority)
    {
        // Hier kannst du überprüfen, ob der Benutzer die entsprechende Rolle hat
        // Beispiel: Verwende Laravel's Role-Check (z.B. spatie/laravel-permission)
        //if (auth()->user()->hasRole($userRole)) {
            $tiles[] = [
                'targetFile' => $targetFile,
                'icon' => $icon,
                'title' => $title,
                'backgroundColor' => $backgroundColor,
                'text' => $text,
                'priority' => $priority
            ];
        //}
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Dashboard $dashboard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dashboard $dashboard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dashboard $dashboard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dashboard $dashboard)
    {
        //
    }
}
