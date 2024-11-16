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
    ],
    'integer' => 'Das Feld :attribute muss eine Zahl sein.',
    'email' => 'Das Feld :attribute muss eine gültige E-Mail-Adresse sein.',
    'nullable' => 'Das Feld :attribute ist optional.',

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
    ],

];
