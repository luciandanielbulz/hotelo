<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Standard-Validierungsnachrichten
    |--------------------------------------------------------------------------
    |
    | Die folgenden Zeilen enthalten Standardfehlermeldungen, die von der
    | Validierungsklasse verwendet werden. Einige Regeln haben mehrere Versionen,
    | wie z. B. die Größenregeln. Passe sie bei Bedarf an.
    |
    */

    'required' => 'Das Feld :attribute ist erforderlich.',
    'required_without' => 'Zumindest das Feld :attribute ist erforderlich.',
    'max' => [
        'string' => 'Das Feld :attribute darf nicht mehr als :max Zeichen enthalten.',
        'file' => 'Die Datei :attribute darf nicht größer als :max Kilobyte sein.',
    ],
    'integer' => 'Das Feld :attribute muss eine Zahl sein.',
    'email' => 'Das Feld :attribute muss eine gültige E-Mail-Adresse sein.',
    'nullable' => 'Das Feld :attribute ist optional.',
    'mimes' => 'Das Feld :attribute muss eine Datei vom Typ: :values sein.',
    'image' => 'Das Feld :attribute muss ein Bild sein.',

    /*
    |--------------------------------------------------------------------------
    | Benutzerdefinierte Validierungsnachrichten
    |--------------------------------------------------------------------------
    |
    | Hier kannst du benutzerdefinierte Nachrichten für Attribute angeben, indem
    | du die Konvention "attribute.rule" verwendest. So kannst du schnell und
    | einfach benutzerdefinierte Nachrichten erstellen.
    |
    */

    'custom' => [
        'customername' => [
            'required_without' => 'Bitte geben Sie einen Kundenname an, wenn kein Firmenname angegeben ist.',
        ],
        'companyname' => [
            'required_without' => 'Bitte geben Sie einen Firmennamen an, wenn kein Kundenname angegeben ist.',
        ],
        'address' => [
            'required' => 'Die Adresse ist erforderlich.',
        ],
        'postalcode' => [
            'required' => 'Die Postleitzahl ist erforderlich.',
            'integer' => 'Die Postleitzahl muss eine Zahl sein.',
        ],
        'location' => [
            'required' => 'Der Ort ist erforderlich.',
        ],
        'logo' => [
            'image' => 'Die hochgeladene Datei muss ein Bild sein.',
            'mimes' => 'Das Bild muss im Format jpeg, png, jpg oder gif sein.',
            'max' => 'Das Bild darf nicht größer als 2MB sein.',
        ],
        'logoheight' => [
            'integer' => 'Die Höhe muss eine Zahl sein.',
            'max' => 'Die Höhe darf nicht mehr als 500 Zeichen enthalten.',
        ],
        'logowidth' => [
            'integer' => 'Die Breite muss eine Zahl sein.',
            'max' => 'Die Breite darf nicht mehr als 500 Zeichen enthalten.',
        ],
        'lastoffer' => [
            'integer' => 'Die Letzte Angebotsnummer muss eine Zahl sein.',
        ],
        'offermultiplikator' => [
            'integer' => 'Die Multiplikator Angebot muss eine Zahl sein.',
        ],
        'lastinvoice' => [
            'integer' => 'Die Letzte Rechnungsnummer muss eine Zahl sein.',
        ],
        'invoicemultiplikator' => [
            'integer' => 'Die Multiplikator Rechnung muss eine Zahl sein.',
        ],
        
    ],

    /*
    |--------------------------------------------------------------------------
    | Attributnamen anpassen
    |--------------------------------------------------------------------------
    |
    | Hier kannst du benutzerdefinierte Namen für Felder angeben, damit diese
    | in den Validierungsnachrichten leserlich und verständlich erscheinen.
    |
    */

    'attributes' => [
        'customername' => 'Kundenname',
        'companyname' => 'Firmenname',
        'address' => 'Adresse',
        'postalcode' => 'Postleitzahl',
        'location' => 'Ort',
        'tax_id' => 'Steuernummer',
        'phone' => 'Telefonnummer',
        'fax' => 'Fax',
        'email' => 'E-Mail-Adresse',
        'condition_id' => 'Bedingung',
        'salutation_id' => 'Anrede',
        'emailsubject' => 'E-Mail-Betreff',
        'emailbody' => 'E-Mail-Text',
        'logo' => 'Logo',
        'logoheight' => 'Logohöhe',
        'logowidth' => 'Logobreite',
    ],

];
