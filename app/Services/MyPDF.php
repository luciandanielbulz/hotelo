<?php

// app/Services/MyPDF.php

namespace App\Services;

use TCPDF;

class MyPDF extends TCPDF
{

    public $footerText = ''; // Variable für benutzerdefinierten Footer-Text
    public $footerHTML = ''; // Variable für HTML-Footer

    // Überschreibe die Footer-Methode von TCPDF
    public function Footer()
    {
        $this->SetY(-29);  // Position 25 mm vom unteren Rand
        $this->SetFont('segoe', '', 8);  // Schriftart und -größe

        // Wenn HTML-Footer gesetzt ist, verwende diesen
        if (!empty($this->footerHTML)) {
            $this->writeHTML($this->footerHTML, true, true, false, true, 'L');
        }

        // Seitennummerierung hinzufügen
        $this->SetXY(180, -35);  // Position 15 mm vom unteren Rand für Seitennummer
        $footerText = 'Seite ' . $this->PageNo() . ' von ' . $this->getNumPages();
        
        // Wenn du benutzerdefinierten Text setzen willst
        if (!empty($this->footerText)) {
            $footerText = $this->footerText; // Nutze den benutzerdefinierten Text
        }

        // Text wird zentriert angezeigt
        $this->MultiCell(0, 10, $footerText, 0, 'C');
    }

    // Methode, um den benutzerdefinierten Footer-Text festzulegen
    public function setCustomFooterText($text)
    {
        $this->footerText = $text;
    }

    // Methode, um HTML-Footer festzulegen
    public function setCustomFooterHTML($html)
    {
        $this->footerHTML = $html;
    }

    public function __construct()
    {
        parent::__construct();
        $this->SetPageFormat(array(210, 297), 'P'); // 200mm x 300mm
    }
}
