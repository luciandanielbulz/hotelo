<?php

// app/Services/MyPDF.php

namespace App\Services;

use TCPDF;

class MyPDF extends TCPDF
{

    public $footerText = ''; // Variable für benutzerdefinierten Footer-Text

    // Überschreibe die Footer-Methode von TCPDF
    public function Footer()
    {
        $this->SetY(-15);  // Position 15 mm vom unteren Rand
        $this->SetFont('helvetica', 'I', 8);  // Schriftart und -größe

        // Erstelle den benutzerdefinierten Footer-Text
        $footerText = 'Seite ' . $this->PageNo() . ' von ' . $this->getNumPages();

        // Wenn du benutzerdefinierten Text setzen willst
        if (!empty($this->footerText)) {
            $footerText = $this->footerText; // Nutze den benutzerdefinierten Text
        }

        // Text wird zentriert angezeigt
        $this->MultiCell(0, 10, $footerText,0,'C');
    }

    // Methode, um den benutzerdefinierten Footer-Text festzulegen
    public function setCustomFooterText($text)
    {
        $this->footerText = $text;
    }
}
