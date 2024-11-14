<?php

// TCPDF-Instanz erstellen
$pdf = new TCPDF();

// Dokumenteinstellungen
$pdf->SetCreator('Laravel');
$pdf->SetAuthor('Dein Name');
$pdf->SetTitle('Test PDF');
$pdf->SetSubject('TCPDF-Test');
$pdf->SetMargins(10, 10, 10);

// Neue Seite hinzufügen
$pdf->AddPage();

// Inhalt hinzufügen
$pdf->SetFont('helvetica', '', 12);
$pdf->Write(0, 'Dies ist ein einfacher TCPDF-Test.');

// PDF ausgeben
?>


<div>
    <!-- The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk -->
</div>
