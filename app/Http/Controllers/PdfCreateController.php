<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offers;
use App\Models\Invoices;
use App\Models\Customer;
use App\Models\Condition;
use App\Models\Taxrates;
use App\Models\Clients;
use App\Models\Offerpositions;
use App\Models\Logos;
use App\Models\Invoicepositions;
use App\Models\OutgoingEmail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use Barryvdh\DomPDF\Facade\Pdf;

/**
 * DomPDF-Implementierung des PdfCreateControllers
 * 
 * Umgestellte PDF-Generierung von TCPDF auf DomPDF.
 * Der ursprüngliche TCPDF-Controller wurde als PdfCreateController_TCPDF_Backup.php gesichert.
 */
class PdfCreateController extends Controller
{
    /**
     * Erstellt ein Angebots-PDF mit DomPDF
     */
    public function createOfferPdf(Request $request)
    {
        $user = Auth::user();
        $clientId = $user->client_id;
        $client = Clients::where('id', $clientId)->firstOrFail();

        $objectId = $request->input('offer_id');
        $preview = $request->input('prev', 'I'); // I: Inline, D: Download, S: String

        // Berechtigung prüfen
        $offer = Offers::from('offers as o')
            ->where('o.id', $objectId)
            ->join('customers as c', 'o.customer_id', '=', 'c.id')
            ->where('c.client_id', $clientId)
            ->first();

        if (!$offer) {
            abort(403, 'Sie haben keine Berechtigung dieses Angebot zu sehen!');
        }

        // Daten sammeln
        $data = $this->gatherOfferData($objectId, $clientId);

        // Logo für DomPDF vorbereiten
        $data['logoPath'] = $this->prepareLogoForDomPDF($client);

        // Direktes HTML generieren (konsistent mit Rechnung)
        $html = $this->generateOfferHTML($data);
        
        // PDF mit DomPDF generieren
        $pdf = Pdf::loadHTML($html);
        
        // Konfiguration
        $pdf->setPaper('A4', 'portrait');
        
        // PDF rendern
        $pdf->render();
        
        // Seitennummerierung nach dem Rendern hinzufügen
        $canvas = $pdf->getDomPDF()->getCanvas();
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            $font = $fontMetrics->get_font('helvetica');
            $size = 10;
            $pageText = 'Seite ' . $pageNumber . ' von ' . $pageCount;
            $y = 710;
            $x = 500;
            $canvas->text($x, $y, $pageText, $font, $size, array(0.5, 0.5, 0.5));
        });
        
        // Je nach Modus ausgeben
        $filename = 'Angebot_' . ($client->offer_prefix ?? '') . $data['offer']->number . '.pdf';
        $pdfOutput = $pdf->getDomPDF()->output();
        
        switch ($preview) {
            case 'D': // Download
                return response($pdfOutput)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
            case 'S': // String (für E-Mail-Versand)
                return response($pdfOutput)
                    ->header('Content-Type', 'application/pdf');
            default: // Inline
                return response($pdfOutput)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
        }
    }

    /**
     * Erstellt ein Rechnungs-PDF mit DomPDF
     */
    public function createInvoicePdf(Request $request)
    {
        $user = Auth::user();
        $clientId = $user->client_id;
        $client = Clients::where('id', $clientId)->firstOrFail();

        $objectId = $request->input('invoice_id');
        $preview = $request->input('prev', 'I');

        // Berechtigung prüfen
        $invoice = Invoices::from('invoices as i')
            ->where('i.id', $objectId)
            ->join('customers as c', 'i.customer_id', '=', 'c.id')
            ->where('c.client_id', $clientId)
            ->first();

        if (!$invoice) {
            abort(403, 'Sie sind nicht berechtigt diese Rechnung zu sehen!');
        }

        // Daten sammeln
        $data = $this->gatherInvoiceData($objectId, $clientId);

        // Logo für DomPDF vorbereiten
        $data['logoPath'] = $this->prepareLogoForDomPDF($client);

        // Direktes HTML generieren (da Blade nicht funktioniert)
        $html = $this->generateInvoiceHTML($data);
        
        // PDF mit DomPDF generieren
        $pdf = Pdf::loadHTML($html);
        
        // Konfiguration
        $pdf->setPaper('A4', 'portrait');
        
        // PDF rendern
        $pdf->render();
        
        // Seitennummerierung nach dem Rendern hinzufügen
        $canvas = $pdf->getDomPDF()->getCanvas();
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            $font = $fontMetrics->get_font('helvetica');
            $size = 10;
            $pageText = 'Seite ' . $pageNumber . ' von ' . $pageCount;
            $y = 710;
            $x = 500;
            $canvas->text($x, $y, $pageText, $font, $size, array(0.5, 0.5, 0.5));
        });

        // Je nach Modus ausgeben
        $filename = 'Rechnung_' . ($client->invoice_prefix ?? '') . $data['invoice']->number . '.pdf';
        $pdfOutput = $pdf->getDomPDF()->output();
        
        switch ($preview) {
            case 'D': // Download
                return response($pdfOutput)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
            case 'S': // String (für E-Mail-Versand)
                return response($pdfOutput)
                    ->header('Content-Type', 'application/pdf');
            default: // Inline
                return response($pdfOutput)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
        }
    }

    /**
     * Sammelt alle für ein Angebot benötigten Daten
     */
    private function gatherOfferData($objectId, $clientId)
    {
        // Angebotsdaten
        $offer = Offers::where('offers.id', $objectId)
            ->join('taxrates', 'offers.tax_id', '=', 'taxrates.id')
            ->first(['offers.*', 'taxrates.*']);

        // Positionen
        $positions = Offerpositions::where('offer_id', $objectId)
            ->join('units', 'offerpositions.unit_id', '=', 'units.id')
            ->orderBy('sequence')
            ->get(['offerpositions.*', 'units.*']);

        // Gesamtsumme
        $totalSum = Offerpositions::where('offer_id', $objectId)
            ->join('units', 'offerpositions.unit_id', '=', 'units.id')
            ->sum(DB::raw('offerpositions.amount * offerpositions.price'));

        // Zusätzliche Daten
        $condition = Condition::join('offers', 'offers.condition_id', '=', 'conditions.id')
            ->where('offers.id', $objectId)
            ->first('conditions.*');

        $customer = Customer::join('offers', 'customers.id', '=', 'offers.customer_id')
            ->where('offers.id', '=', $objectId)
            ->first('customers.*');

        $client = Clients::where('id', $clientId)->first();

        return [
            'offer' => $offer,
            'positions' => $positions,
            'totalSum' => $totalSum,
            'condition' => $condition,
            'customer' => $customer,
            'client' => $client,
            'taxRate' => $offer->taxrate,
            'formattedDate' => $this->formatDate($offer->date),
        ];
    }

    /**
     * Sammelt alle für eine Rechnung benötigten Daten
     */
    private function gatherInvoiceData($objectId, $clientId)
    {
        // Rechnungsdaten
        $invoice = Invoices::join('taxrates', 'invoices.tax_id', '=', 'taxrates.id')
            ->where('invoices.id', $objectId)
            ->first(['invoices.*', 'taxrates.*']);

        // Positionen
        $positions = Invoicepositions::where('invoice_id', $objectId)
            ->join('units', 'invoicepositions.unit_id', '=', 'units.id')
            ->orderBy('sequence')
            ->get(['invoicepositions.*', 'units.*']);

        // Gesamtsumme
        $totalSum = Invoicepositions::where('invoice_id', $objectId)
            ->join('units', 'invoicepositions.unit_id', '=', 'units.id')
            ->sum(DB::raw('invoicepositions.amount * invoicepositions.price'));

        // Zusätzliche Daten
        $condition = Condition::join('invoices', 'invoices.condition_id', '=', 'conditions.id')
            ->where('invoices.id', $objectId)
            ->first('conditions.*');

        $customer = Customer::join('invoices', 'customers.id', '=', 'invoices.customer_id')
            ->where('invoices.id', '=', $objectId)
            ->first('customers.*');

        $client = Clients::where('id', $clientId)->first();

        return [
            'invoice' => $invoice,
            'positions' => $positions,
            'totalSum' => $totalSum,
            'condition' => $condition,
            'customer' => $customer,
            'client' => $client,
            'taxRate' => $invoice->taxrate,
            'reverseCharge' => $invoice->reverse_charge ?? false,
            'depositAmount' => $invoice->depositamount ?? 0,
            'formattedDate' => $this->formatDate($invoice->date),
            'formattedPeriodFrom' => $this->formatDate($invoice->periodfrom),
            'formattedPeriodTo' => $this->formatDate($invoice->periodto),
        ];
    }

    /**
     * Bereitet Logo für DomPDF vor
     * DomPDF hat andere Anforderungen an Bilder als TCPDF
     */
    private function prepareLogoForDomPDF($client)
    {
        if (!$client->logo) {
            return null;
        }

        // Mehrere mögliche Pfade prüfen
        $possiblePaths = [
            storage_path('app/public/logos/' . $client->logo),
            public_path('logo/' . $client->logo),
            storage_path('app/logos/' . $client->logo),
            public_path('logos/' . $client->logo),
        ];
        
        $logoPath = null;
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                $logoPath = $path;
                break;
            }
        }
        
        if (!$logoPath) {
            \Log::warning('Logo nicht gefunden: ' . $client->logo . ' in keinem der Pfade: ' . implode(', ', $possiblePaths));
            return null;
        }

        // Für DomPDF: Versuche verschiedene Ansätze
        try {
            // Zunächst versuchen wir das Logo in das public-Verzeichnis zu kopieren
            $publicLogoPath = public_path('temp_logos');
            if (!file_exists($publicLogoPath)) {
                mkdir($publicLogoPath, 0755, true);
            }
            
            $tempLogoFile = $publicLogoPath . '/' . basename($client->logo);
            copy($logoPath, $tempLogoFile);
            
            // Relativer URL-Pfad für DomPDF
            $relativePath = 'temp_logos/' . basename($client->logo);
            
            \Log::info('Logo temporär kopiert: ' . $tempLogoFile . ' -> ' . $relativePath);
            return $relativePath;
            
        } catch (\Exception $e) {
            \Log::error('Fehler bei der Logo-Verarbeitung für DomPDF: ' . $e->getMessage());
            
            // Fallback: Base64
            try {
                $imageData = file_get_contents($logoPath);
                $base64 = base64_encode($imageData);
                $mimeType = mime_content_type($logoPath);
                
                return 'data:' . $mimeType . ';base64,' . $base64;
            } catch (\Exception $e2) {
                \Log::error('Auch Base64-Fallback fehlgeschlagen: ' . $e2->getMessage());
                return null;
            }
        }
    }

    /**
     * Generiert eine intelligente Fußzeile für PDFs
     * Leere Felder werden übersprungen und Zeilen rücken nach oben
     */
    private function generateFooter($client)
    {
        // Spalte 1: Firmenadresse
        $addressRows = [];
        if (!empty($client->companyname)) {
            $addressRows[] = '<tr><td style="text-align: left;">' . htmlspecialchars($client->companyname) . '</td></tr>';
        }
        if (!empty($client->address)) {
            $addressRows[] = '<tr><td style="text-align: left;">' . htmlspecialchars($client->address) . '</td></tr>';
        }
        if (!empty($client->postalcode) && !empty($client->location)) {
            $addressRows[] = '<tr><td style="text-align: left;">' . htmlspecialchars($client->postalcode . ' ' . $client->location) . '</td></tr>';
        }
        // Österreich als feste Zeile, falls gewünscht
        $addressRows[] = '<tr><td style="text-align: left;">Österreich</td></tr>';

        // Spalte 2: Kontaktdaten
        $contactRows = [];
        if (!empty($client->phone)) {
            $contactRows[] = '<tr><td style="text-align: left;">Tel.: ' . htmlspecialchars($client->phone) . '</td></tr>';
        }
        if (!empty($client->email)) {
            $contactRows[] = '<tr><td style="text-align: left;">E.Mail: ' . htmlspecialchars($client->email) . '</td></tr>';
        }
        if (!empty($client->webpage)) {
            $contactRows[] = '<tr><td style="text-align: left;">Web: ' . htmlspecialchars($client->webpage) . '</td></tr>';
        }

        // Spalte 3: Rechtliche Informationen
        $legalRows = [];
        if (!empty($client->regional_court)) {
            $legalRows[] = '<tr><td style="text-align: left;">' . htmlspecialchars($client->regional_court) . '</td></tr>';
        }
        if (!empty($client->company_registration_number)) {
            $legalRows[] = '<tr><td style="text-align: left;">FN-Nr.: ' . htmlspecialchars($client->company_registration_number) . '</td></tr>';
        }
        if (!empty($client->vat_number)) {
            $legalRows[] = '<tr><td style="text-align: left;">USt.-ID: ' . htmlspecialchars($client->vat_number) . '</td></tr>';
        }
        if (!empty($client->tax_number)) {
            $legalRows[] = '<tr><td style="text-align: left;">Steuer-Nr.: ' . htmlspecialchars($client->tax_number) . '</td></tr>';
        }
        if (!empty($client->management)) {
            $legalRows[] = '<tr><td style="text-align: left;">Geschäftsführung: ' . htmlspecialchars_decode($client->management) . '</td></tr>';
        }

        // Spalte 4: Bankdaten
        $bankRows = [];
        if (!empty($client->bank)) {
            $bankRows[] = '<tr><td style="text-align: left;">' . htmlspecialchars($client->bank) . '</td></tr>';
        }
        if (!empty($client->accountnumber)) {
            $bankRows[] = '<tr><td style="text-align: left;">IBAN: ' . htmlspecialchars($client->accountnumber) . '</td></tr>';
        }
        if (!empty($client->bic)) {
            $bankRows[] = '<tr><td style="text-align: left;">BIC: ' . htmlspecialchars($client->bic) . '</td></tr>';
        }

        // Footer zusammenbauen
        $footer = '
            <div style="position: fixed; bottom: 0; left: 0; right: 0; padding: 10px; border-top: 0px solid #ccc; background: white;">
                <table cellpadding="0" cellspacing="0" width="100%" style="font-size: 11px; color: grey">
                    <tr>
                        <td width="25%" style="vertical-align: top;">
                            <table cellpadding="0.5" cellspacing="1" width="100%" style=" color: grey">
                                ' . implode('', $addressRows) . '
                            </table>
                        </td>
                        <td width="25%" style="vertical-align: top;">
                            <table cellpadding="0.5" cellspacing="1" width="100%" style=" color: grey">
                                ' . implode('', $contactRows) . '
                            </table>
                        </td>
                        <td width="25%" style="vertical-align: top;">
                            <table cellpadding="0.5" cellspacing="1" width="100%" style=" color: grey">
                                ' . implode('', $legalRows) . '
                            </table>
                        </td>
                        <td width="25%" style="vertical-align: top;">
                            <table cellpadding="0.5" cellspacing="1" width="100%" style=" color: grey">
                                ' . implode('', $bankRows) . '
                            </table>
                        </td>
                    </tr>
                </table>
            </div>';

        return $footer;
    }

    /**
     * Formatiert Datum mit Carbon
     */
    private function formatDate($date)
    {
        return $date ? \Carbon\Carbon::parse($date)->format('d.m.Y') : '';
    }

    /**
     * Generiert HTML für Rechnung direkt (da Blade nicht funktioniert)
     */
    private function generateInvoiceHTML($data)
    {
        extract($data); // $invoice, $customer, $client, $positions, etc.
        
        $clientColor = $client->color ?? '#000000';
        $headerTextColor = $clientColor !== '#000000' ? 'white' : 'black';
        
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rechnung ' . htmlspecialchars($invoice->number) . '</title>
    <style>
        @page { margin: 10mm; }
        body { font-family:  Roboto, sans-serif;font-size: 12px; line-height: 1.2; margin: 0; padding: 0; margin-bottom: 60px; }
        .header { width: 100%; position: relative; height: 110px; margin-bottom: 30px; }
        .logo { position: absolute; top: 0; left: 0; height: 40px; max-width: 150px; }
        .company-info { position: absolute; top: 135px; left: 0; font-size: 8px; color: black; }
        
        .document-info { position: absolute; top: 150px; right: 0; width: 250px; text-align: right;}
        .document-info table { width: 100%; border-collapse: collapse; font-size: 10px; }
        .document-info td { padding: 2px 5px;}
        
        .operation-info { position: absolute; top: 215px; right: 0; width: 250px; text-align: right;}
        .operation-info table { width: 100%; border-collapse: collapse; font-size: 10px;}
        .operation-info td { padding: 2px 5px; }
        
        .customer-address { margin: 20px 0; margin-top: 75px; width: 50%; font-size: 12px;}
        .customer-address table { width: 100%; border-collapse: collapse; }
        .customer-address td { padding: 2px 0; border: none; }
        .document-title { font-size: 20px; font-weight: bold; margin: 30px 0 15px 0; margin-top: 50px; color: ' . $clientColor . '; }
        .intro-text { margin: 15px 0; font-size: 12px;}
        .positions-table { width: 100%; border-collapse: collapse; margin: 20px 0; margin-top: 0px; }
        .positions-header { background-color: ' . $clientColor . '; color: ' . $headerTextColor . '; font-weight: bold; }
        .positions-header td { padding: 5px; font-size: 12px; }
        .positions-body td { padding: 5px; vertical-align: top; font-size: 12px; }
        .totals-table { width: 100%; border-collapse: collapse; border-top: 1px solid ' . $clientColor . '}
        .totals-table td { padding: 3px 5px; font-size: 12px; }
        .total-final { font-weight: bold; color: ' . $clientColor . '; font-size: 12px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .reverse-charge-note { font-weight: bold; color: red; margin: 10px 0; }
    </style>
</head>
<body>';

        // Header
        $html .= '<div class="header">';
        if ($logoPath) {
            $html .= '<img src="' . $logoPath . '" alt="Logo" style="position: absolute; top: 0; right: 0; max-height: 100px; max-width: 200px; height: auto; width: auto;">';
        } else {
            // Fallback: Firmenname als "Logo"  
            $html .= '<div style="position: absolute; top: 0; left: 0; font-size: 16px; font-weight: bold; color: ' . $clientColor . ';">' . 
                     htmlspecialchars($client->companyname) . '</div>';
        }
        $html .= '<div class="company-info">' . 
                 htmlspecialchars($client->companyname) . ' - ' . 
                 htmlspecialchars($client->address) . ' - ' . 
                 htmlspecialchars($client->postalcode) . ' ' . 
                 htmlspecialchars($client->location) . '</div>';
        
        $html .= '<div class="document-info">
            <table style="font-size: 14px;">
                <tr><td class="text-left">Rechnungs-Nr.</td><td class="text-right">' . htmlspecialchars(($client->invoice_prefix ?? '') . $invoice->number) . '</td></tr>
            </table>
            <table style="font-size: 10px;">
                <tr><td class="text-left">Rechnungsdatum</td><td class="text-right">' . htmlspecialchars($formattedDate) . '</td></tr>';
        
        if ($formattedPeriodFrom && $formattedPeriodTo) {
            $html .= '<tr><td class="text-left">Leistungszeitraum</td><td class="text-right">' . 
                     htmlspecialchars($formattedPeriodFrom) . ' - ' . 
                     htmlspecialchars($formattedPeriodTo) . '</td></tr>';
        }
        
        $html .= '</table></div></div>';

        // Customer Address
        $html .= '<div class="customer-address"><table>';
        if ($customer->companyname) $html .= '<tr><td>' . htmlspecialchars($customer->companyname) . '</td></tr>';
        if ($customer->customername) $html .= '<tr><td>' . htmlspecialchars($customer->customername) . '</td></tr>';
        if ($customer->address) $html .= '<tr><td>' . htmlspecialchars($customer->address) . '</td></tr>';
        if ($customer->postalcode || $customer->location) {
            $html .= '<tr><td>' . htmlspecialchars($customer->postalcode) . ' ' . htmlspecialchars($customer->location) . '</td></tr>';
        }
        if ($customer->country) $html .= '<tr><td>' . htmlspecialchars($customer->country) . '</td></tr>';
        $html .= '</table></div>';

        // Operation Info (weiter nach unten verschoben)
        $html .= '<div class="operation-info" ><table>';
        if ($condition) {
            $html .= '<tr><td class="text-left">Zahlungskonditionen</td><td class="text-right">' . htmlspecialchars($condition->conditionname) . '</td></tr>';
        }
        if ($customer->customer_number) {
            $html .= '<tr><td class="text-left">Ihre Kundennummer</td><td class="text-right">' . htmlspecialchars($customer->customer_number) . '</td></tr>';
        }
        if ($customer->vat_number) {
            $html .= '<tr><td class="text-left">Ihre USt-Id.</td><td class="text-right">' . htmlspecialchars($customer->vat_number) . '</td></tr>';
        }
        $html .= '</table></div>';

        // Document Title
        $html .= '<div class="document-title">Rechnung Nr. ' . htmlspecialchars(($client->invoice_prefix ?? '') . $invoice->number) . '</div>';

        // Intro Text
        $html .= '<div class="intro-text">Vielen Dank für Ihren Auftrag und das damit verbundene Vertrauen! Hiermit stellen wir Ihnen die folgenden Leistungen in Rechnung:</div>';

        // Reverse Charge Notice
        //if ($reverseCharge) {
            //$html .= '<div class="reverse-charge-note">REVERSE CHARGE - Steuerschuldnerschaft des Leistungsempfängers</div>';
        //}

        // Positions Table
        $html .= '<table class="positions-table">
            <tr class="positions-header">
                <td style="width: 5%;">Pos.</td>
                <td style="width: 48%;">Bezeichnung</td>
                <td style="width: 12%; text-align: right;">Menge</td>
                <td style="width: 15%; text-align: right;">Einzelpreis</td>
                <td style="width: 15%; text-align: right;">Gesamtpreis</td>
            </tr>';

        $positionNumber = 1;
        foreach ($positions as $position) {
            if ($position->positiontext == 1) {
                $html .= '<tr class="positions-body">
                    <td colspan="5" style="text-align: center; font-weight: bold;">' . 
                    nl2br(htmlspecialchars($position->details ?? '')) . '</td>
                </tr>';
            } else {
                $html .= '<tr class="positions-body">
                    <td class="text-center" >' . $positionNumber . '.</td>
                    <td style="font-weight: bold;">' . htmlspecialchars($position->designation);
                
                if ($position->details) {
                    $html .= '<div style="font-size: 12px; font-weight: normal; color: #000; margin-top: 3px;">' . 
                             nl2br(htmlspecialchars($position->details)) . '</div>';
                }
                
                $html .= '</td>
                    <td class="text-right">' . number_format($position->amount, 2, ',', '') . ' ' . 
                             htmlspecialchars($position->unitdesignation ?? $position->unitname ?? '') . '</td>
                    <td class="text-right">' . number_format($position->price, 2, ',', '') . ' EUR</td>
                    <td class="text-right">' . number_format($position->price * $position->amount, 2, ',', '') . ' EUR</td>
                </tr>';
                $positionNumber++;
            }
        }
        $html .= '</table>';

        // Totals
        $html .= '<table class="totals-table">
            <tr>
                <td style="width: 5%;"></td>
                <td style="width: 80%; color: ' . $clientColor . ';">Gesamtbetrag netto</td>
                <td style="width: 15%; text-align: right; color: ' . $clientColor . ';">' . 
                number_format($totalSum, 2, ',', '') . ' EUR</td>
            </tr>';

        if (!$reverseCharge) {
            $html .= '<tr>
                <td></td>
                <td>zzgl. Umsatzsteuer ' . $taxRate . '%</td>
                <td class="text-right">' . number_format($totalSum * $taxRate / 100, 2, ',', '') . ' EUR</td>
            </tr>';
        } else {
            $html .= '<tr>
                <td></td>
                <td>zzgl. Umsatzsteuer ' . $taxRate . '%</td>
                <td class="text-right">' . number_format($totalSum * $taxRate / 100, 2, ',', '') . ' EUR</td>
            </tr>';
        }

        $finalLabel = $reverseCharge ? 'Gesamtbetrag netto (Reverse Charge)' : 'Gesamtbetrag brutto';
        $finalAmount = $reverseCharge ? $totalSum : $totalSum * ($taxRate / 100 + 1);

        $html .= '<tr class="total-final">
            <td></td>
            <td>' . $finalLabel . '</td>
            <td class="text-right">' . number_format($finalAmount, 2, ',', '') . ' EUR</td>
        </tr>';

        if ($depositAmount > 0) {
            $html .= '<tr>
                <td></td>
                <td style="border-bottom: 0.5px solid black;">Anzahlung</td>
                <td class="text-right" style="border-bottom: 0.5px solid black;">-' . 
                number_format($depositAmount, 2, ',', '') . ' EUR</td>
            </tr>';
            
            $finalPayment = $finalAmount - $depositAmount;
            $html .= '<tr class="total-final">
                <td></td>
                <td style="border-bottom: 2px solid ' . $clientColor . ';">Zu zahlen:</td>
                <td class="text-right" style="border-bottom: 2px solid ' . $clientColor . ';">' . 
                number_format($finalPayment, 2, ',', '') . ' EUR</td>
            </tr>';
        }

        $html .= '</table>';

        if ($reverseCharge) {
            $html .= '<div style="margin-top: 20px; font-size: 12px;">Gemäß § 13b UStG liegt die Steuerschuldnerschaft beim Leistungsempfänger.</div>';
        }

        // Footer hinzufügen
        $footer = $this->generateFooter($client);
        $html .= $footer;

        $html .= '</body></html>';
        
        return $html;
    }

    /**
     * Generiert HTML für Angebot direkt
     */
    private function generateOfferHTML($data)
    {
        extract($data); // $offer, $customer, $client, $positions, etc.
        
        $clientColor = $client->color ?? '#000000';
        $headerTextColor = $clientColor !== '#000000' ? 'white' : 'black';
        
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Angebot ' . htmlspecialchars($offer->number) . '</title>
    <style>
        @page { margin: 10mm; }
        body { font-family:  Roboto, sans-serif;font-size: 12px; line-height: 1.2; margin: 0; padding: 0; margin-bottom: 60px; }
        .header { width: 100%; position: relative; height: 110px; margin-bottom: 30px; }
        .logo { position: absolute; top: 0; left: 0; max-height: 50px; max-width: 150px; }
        .company-info { position: absolute; top: 135px; left: 0; font-size: 8px; color: black; }
        
        .document-info { position: absolute; top: 150px; right: 0; width: 250px; text-align: right;}
        .document-info table { width: 100%; border-collapse: collapse; font-size: 10px; }
        .document-info td { padding: 2px 5px;}
        
        .operation-info { position: absolute; top: 215px; right: 0; width: 250px; text-align: right;}
        .operation-info table { width: 100%; border-collapse: collapse; font-size: 10px;}
        .operation-info td { padding: 2px 5px; }
        
        .customer-address { margin: 20px 0; margin-top: 75px; width: 50%; font-size: 12px;}
        .customer-address table { width: 100%; border-collapse: collapse; }
        .customer-address td { padding: 2px 0; border: none; }
        .document-title { font-size: 20px; font-weight: bold; margin: 30px 0 15px 0; margin-top: 50px; color: ' . $clientColor . '; }
        .intro-text { margin: 15px 0; font-size: 12px;}
        .positions-table { width: 100%; border-collapse: collapse; margin: 20px 0; margin-top: 0px; }
        .positions-header { background-color: ' . $clientColor . '; color: ' . $headerTextColor . '; font-weight: bold; }
        .positions-header td { padding: 5px; font-size: 12px; }
        .positions-body td { padding: 5px; vertical-align: top; font-size: 12px; }
        .totals-table { width: 100%; border-collapse: collapse; border-top: 1px solid ' . $clientColor . '}
        .totals-table td { padding: 3px 5px; font-size: 12px; }
        .total-final { font-weight: bold; color: ' . $clientColor . '; font-size: 12px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .signature-section { margin-top: 40px; font-size: 10px; }
        .signature-line { margin-top: 30px; border-bottom: 1px solid black; width: 300px; height: 20px; }
    </style>
</head>
<body>';

        // Header
        $html .= '<div class="header">';
        if ($logoPath) {
            $html .= '<img src="' . $logoPath . '" alt="Logo" style="position: absolute; top: 0; right: 0; max-height: 100px; max-width: 200px; height: auto; width: auto;">';
        } else {
            // Fallback: Firmenname als "Logo"  
            $html .= '<div style="position: absolute; top: 0; left: 0; font-size: 16px; font-weight: bold; color: ' . $clientColor . ';">' . 
                     htmlspecialchars($client->companyname) . '</div>';
        }
        $html .= '<div class="company-info">' . 
                 htmlspecialchars($client->companyname) . ' - ' . 
                 htmlspecialchars($client->address) . ' - ' . 
                 htmlspecialchars($client->postalcode) . ' ' . 
                 htmlspecialchars($client->location) . '</div>';
        
        $html .= '<div class="document-info">
            <table style="font-size: 14px;">
                <tr><td class="text-left">Angebots-Nr.</td><td class="text-right">' . htmlspecialchars(($client->offer_prefix ?? '') . $offer->number) . '</td></tr>
            </table>
            <table style="font-size: 10px;">
                <tr><td class="text-left">Angebotsdatum</td><td class="text-right">' . htmlspecialchars($formattedDate) . '</td></tr>
                <tr><td class="text-left">Gültigkeitsdauer</td><td class="text-right">3 Wochen</td></tr>
            </table>
        </div></div>';

        // Customer Address
        $html .= '<div class="customer-address"><table>';
        if ($customer->companyname) $html .= '<tr><td>' . htmlspecialchars($customer->companyname) . '</td></tr>';
        if ($customer->customername) $html .= '<tr><td>' . htmlspecialchars($customer->customername) . '</td></tr>';
        if ($customer->address) $html .= '<tr><td>' . htmlspecialchars($customer->address) . '</td></tr>';
        if ($customer->postalcode || $customer->location) {
            $html .= '<tr><td>' . htmlspecialchars($customer->postalcode) . ' ' . htmlspecialchars($customer->location) . '</td></tr>';
        }
        if ($customer->country) $html .= '<tr><td>' . htmlspecialchars($customer->country) . '</td></tr>';
        $html .= '</table></div>';

        // Operation Info
        $html .= '<div class="operation-info"><table>';
        if ($condition) {
            $html .= '<tr><td class="text-left">Zahlungskonditionen</td><td class="text-right">' . htmlspecialchars($condition->conditionname) . '</td></tr>';
        }
        if ($customer->customer_number) {
            $html .= '<tr><td class="text-left">Ihre Kundennummer</td><td class="text-right">' . htmlspecialchars($customer->customer_number) . '</td></tr>';
        }
        if ($customer->vat_number) {
            $html .= '<tr><td class="text-left">Ihre USt-Id.</td><td class="text-right">' . htmlspecialchars($customer->vat_number) . '</td></tr>';
        }
        $html .= '</table></div>';

        // Document Title
        $html .= '<div class="document-title">Angebot ' . htmlspecialchars(($client->offer_prefix ?? '') . $offer->number) . '</div>';

        // Intro Text
        $html .= '<div class="intro-text">Unter Einhaltung unserer allg. Geschäftsbedingungen, erlauben wir uns, Ihnen folgendes Angebot zu unterbreiten:</div>';

        // Positions Table
        $html .= '<table class="positions-table">
            <tr class="positions-header">
                <td style="width: 5%;">Pos.</td>
                <td style="width: 48%;">Bezeichnung</td>
                <td style="width: 12%; text-align: right;">Menge</td>
                <td style="width: 15%; text-align: right;">Einzelpreis</td>
                <td style="width: 15%; text-align: right;">Gesamtpreis</td>
            </tr>';

        $positionNumber = 1;
        foreach ($positions as $position) {
            if ($position->positiontext == 1) {
                $html .= '<tr class="positions-body">
                    <td colspan="5" style="text-align: center; font-weight: bold;">' . 
                    nl2br(htmlspecialchars($position->details ?? '')) . '</td>
                </tr>';
            } else {
                $html .= '<tr class="positions-body">
                    <td class="text-center" >' . $positionNumber . '.</td>
                    <td style="font-weight: bold;">' . htmlspecialchars($position->designation);
                
                if ($position->details) {
                    $html .= '<div style="font-size: 12px; font-weight: normal; color: #000; margin-top: 3px;">' . 
                             nl2br(htmlspecialchars($position->details)) . '</div>';
                }
                
                $html .= '</td>
                    <td class="text-right">' . number_format($position->amount, 2, ',', '') . ' ' . 
                             htmlspecialchars($position->unitdesignation ?? $position->unitname ?? '') . '</td>
                    <td class="text-right">' . number_format($position->price, 2, ',', '') . ' EUR</td>
                    <td class="text-right">' . number_format($position->price * $position->amount, 2, ',', '') . ' EUR</td>
                </tr>';
                $positionNumber++;
            }
        }
        $html .= '</table>';

        // Totals
        $html .= '<table class="totals-table">
            <tr>
                <td style="width: 5%;"></td>
                <td style="width: 80%; color: ' . $clientColor . ';">Gesamtbetrag netto</td>
                <td style="width: 15%; text-align: right; color: ' . $clientColor . ';">' . 
                number_format($totalSum, 2, ',', '') . ' EUR</td>
            </tr>
            <tr>
                <td></td>
                <td>zzgl. Umsatzsteuer ' . $taxRate . '%</td>
                <td class="text-right">' . number_format($totalSum * $taxRate / 100, 2, ',', '') . ' EUR</td>
            </tr>
            <tr class="total-final">
                <td></td>
                <td>Gesamtbetrag brutto</td>
                <td class="text-right">' . number_format($totalSum * ($taxRate / 100 + 1), 2, ',', '') . ' EUR</td>
            </tr>
        </table>';

        // Signature Section
        $html .= '<div class="signature-section">
            Bei Annahme des Angebots bitten wir um Unterfertigung
            <div class="signature-line"></div>
            <div style="margin-top: 5px;">Unterschrift Kunde</div>
        </div>';

        // Footer hinzufügen
        $footer = $this->generateFooter($client);
        $html .= $footer;

        $html .= '</body></html>';
        
        return $html;
    }

    /**
     * E-Mail-Versand mit DomPDF
     */
    public function sendOfferByEmail(Request $request)
    {
        return $this->sendDocumentByEmailDomPDF($request, 'offer');
    }

    public function sendInvoiceByEmail(Request $request)
    {
        return $this->sendDocumentByEmailDomPDF($request, 'invoice');
    }

    /**
     * Generische E-Mail-Versand-Logik mit DomPDF
     */
    private function sendDocumentByEmailDomPDF($request, $documentType)
    {
        // Validierung der Eingabedaten
        $documentIdField = $documentType . '_id';
        $request->validate([
            $documentIdField => 'required|integer|exists:' . $documentType . 's,id',
            'email' => 'required|email',
            'subject' => 'required|string',
            'copy_email' => 'nullable|email',
            'message' => 'required|string',
        ]);

        // Dynamische Datenabfrage basierend auf Dokumenttyp
        $modelClass = $documentType === 'invoice' ? Invoices::class : Offers::class;
        $tableName = $documentType . 's';
        
        $documentData = $modelClass::join('customers', 'customers.id', '=', $tableName . '.customer_id')
            ->join('clients', 'clients.id', '=', 'customers.client_id')
            ->where($tableName . '.id', '=', $request->input($documentIdField))
            ->select(
                'customers.*',
                $tableName . '.*',
                'customers.id as customer_id',
                'clients.email as senderemail',
                'clients.companyname as clientname',
                $tableName . '.number as document_number'
            )
            ->first();

        if (!$documentData || !$documentData->senderemail) {
            return response()->json(['message' => 'Client oder Absender-E-Mail nicht gefunden.'], 404);
        }

        // PDF generieren mit DomPDF
        $request->merge(['prev' => 'S']);
        $pdfMethod = 'create' . ucfirst($documentType) . 'Pdf';
        $pdfResponse = $this->$pdfMethod($request);
        $pdfContent = $pdfResponse->getContent();

        // Datei speichern und versenden
        $randomFileName = Str::random(40) . '.pdf';
        $storagePath = storage_path('app/objects');
        
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        $filePath = $storagePath . '/' . $randomFileName;

        try {
            file_put_contents($filePath, $pdfContent);
        } catch (\Exception $e) {
            \Log::error('Fehler beim Speichern der PDF: ' . $e->getMessage());
            return response()->json(['message' => 'Fehler beim Speichern des Dokuments.'], 500);
        }

        // E-Mail versenden (gleiche Logik wie im ursprünglichen Controller)
        $sentDate = now();
        $status = false;

        try {
            $email = $request->input('email');
            $subject = $request->input('subject');
            $messageBody = $request->input('message');
            $ccEmail = $request->input('copy_email');
            $documentNameGerman = $documentType === 'invoice' ? 'Rechnung' : 'Angebot';

            // Client-Daten für Präfixe laden
            $client = Clients::find($request->user()->client_id);
            $prefix = '';
            if ($documentType === 'invoice') {
                $prefix = $client->invoice_prefix ?? '';
            } elseif ($documentType === 'offer') {
                $prefix = $client->offer_prefix ?? '';
            }

            Mail::send([], [], function ($message) use (
                $randomFileName, $documentData, $email, $subject, $messageBody, 
                $filePath, $ccEmail, $documentNameGerman, $prefix
            ) {
                $message->from($documentData->senderemail, $documentData->clientname)
                        ->to($email)
                        ->subject($subject)
                        ->html($messageBody)
                        ->attach($filePath, [
                            'as' => $documentNameGerman . '_' . $prefix . $documentData->document_number . '.pdf',
                            'mime' => 'application/pdf',
                        ]);

                if (!empty($ccEmail)) {
                    $message->bcc($ccEmail);
                }
            });

            $status = true;

        } catch (\Exception $e) {
            \Log::error('Fehler beim E-Mail-Versand: ' . $e->getMessage());
        }

        // Datenbankeintraag
        OutgoingEmail::create([
            'type' => $documentType === 'invoice' ? 1 : 2,
            'customer_id' => $documentData->customer_id,
            'objectnumber' => $documentData->document_number,
            'sentdate' => $sentDate,
            'getteremail' => $email,
            'filename' => $randomFileName,
            'withattachment' => true,
            'status' => $status,
            'client_id' => $request->user()->client_id,
        ]);

        $documentNameGerman = $documentType === 'invoice' ? 'Rechnung' : 'Angebot';
        return redirect()->route('outgoingemails.index')
                        ->with('success', $documentNameGerman . ' wurde erfolgreich per E-Mail versendet.');
    }
} 