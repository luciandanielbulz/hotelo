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
use App\Services\MyPDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\Exception;
use Intervention\Image\Facades\Image;






class PdfCreateController extends Controller
{

        public function createOfferPdf(Request $request)
    {
        
        $user = Auth::user();
        $clientId = $user->client_id;


        $client = Clients::where('id', $clientId)->firstOrFail();

        if ($client->color <> '#000000') {
            $color = 'white';
        } else {
            $color = 'black';
        }

        $font = 'arial';
        $fontbold = 'segoebd';

        $objectId = $request->input('offer_id');
        $objectType = $request->input('objecttype'); // "offer" oder "invoice"
        $preview = $request->input('prev', 0); // 0: Download, 1: Vorschau, 2: Speichern
        //dd($objectId);
        
        $offer = Offers::from('offers as o')
            ->where('o.id', $objectId)
            ->join('customers as c', 'o.customer_id', '=', 'c.id')
            ->where('c.client_id', $clientId)
            ->first();
        
        if (!$offer) {
            abort(403, 'Sie haben keine Berechtigung dieses Angebot zu sehen!');
        }

        $offercontent = Offers::where('offers.id', $objectId)
            ->join('taxrates','offers.tax_id','=','taxrates.id')
            ->first(['offers.*','taxrates.*']);

        //dd($offercontent);

        $positions = Offerpositions::where('offer_id', $objectId)
            ->join('units','offerpositions.unit_id','=','units.id')
            ->orderBy('sequence')
            ->get(['offerpositions.*','units.*']);
        //dd($positions);

        $totalSum = Offerpositions::where('offer_id', $objectId)
            ->join('units', 'offerpositions.unit_id', '=', 'units.id')
            ->sum(DB::raw('offerpositions.amount * offerpositions.price'));

        $condition = Condition::join('offers','offers.condition_id','=','conditions.id')
            ->first('conditions.*');


        

        $customer = Customer::join('offers','customers.id','=','offers.customer_id')
            ->where('offers.id','=',$objectId)
            ->first('customers.*');

        // Logo-Pfad aus der clients-Tabelle
        $localImagePath = $client->logo ? storage_path('app/public/logos/' . $client->logo) : null;
        $imageHeight = $client->logoheight;
        $imageWidth = $client->logowidth;

        // Client-Farbe für PDF verwenden
        $clientColor = $client->color ?? '#000000';

        $clientdata = '
            <table cellpadding="2" cellspacing="0" width = "300">
                <tr>
                    <td style="text-align: left; color: black;">'.$client->companyname.' - '.$client->address.' - '.$client->postalcode.' '.$client->location.'</td>
                </tr>
            </table>';

        $customerdata = '
            <table cellpadding="0" cellspacing="0" style="width:100%;">
                <tr>
                    <td style="text-align: left;">' . ($customer->companyname ?? '') . '</td>
                </tr>
                <tr>
                    <td style="text-align: left;">' . ($customer->customername ?? '') . '</td>
                </tr>
                <tr>
                    <td style="text-align: left;">' . ($customer->address ?? '') . '</td>
                </tr>
                <tr>
                    <td style="text-align: left;">' . ($customer->postalcode ?? '') . ' ' . ($customer->location ?? '') . '</td>
                </tr>
                <tr>
                    <td style="text-align: left;">' . ($customer->country ?? '') . '</td>
                </tr>
            </table>';
        $clienttable = '
            <table cellpadding="2" cellspacing="0" width = "222" style=" background-color: rgb(243, 243, 243);">
                <tr>
                    <td style="text-align: right;">Firmenname: '.$client->companyname.'</td>
                </tr>
                <tr>
                    <td style="text-align: right;">Registrierzahl: </td>
                </tr>
                <tr>
                    <td style="text-align: right;">Geschäftsform: '.$client->business.'</td>
                </tr>
                <tr>
                    <td style="text-align: right;">Adresse: '.$client->address.'</td>
                </tr>
                <tr>
                    <td style="text-align: right;">Postleitzahl und Ort: '.$client->postalcode." ".$client->location.'</td>
                </tr>
                <tr>
                    <td style="text-align: right;">E-Mail: '.$client->email.'</td>
                </tr>
                <tr>
                    <td style="text-align: right;">Telefon: '.$client->phone.'</td>
                </tr>
            </table>';

            $operation = '
            <table cellpadding="0.5" width="197" style="border: 0.5px solid white; width: 100%;">
                
                <tr>
                    <td style="text-align: left;">Zahlungskonditionen:</td>
                    <td style="text-align: right;">' . ($condition->conditionname ?? '') . '</td>
                </tr>
                <tr>
                    <td style="text-align: left;">Ihre Kundennummer:</td>
                    <td style="text-align: right;">' . ($customer->customernumber ?? '') . '</td>
                </tr>
                <tr>
                    <td style="text-align: left;">Ihre USt-ID:</td>
                    <td style="text-align: right;">' . ($customer->vat_number ?? '') . '</td>
                </tr>
            </table>';

        $formattedDate = \Carbon\Carbon::parse($offercontent->date)->format('d.m.Y');

        $offerNumber = '
            <table cellpadding="0.5" cellspacing="0" width = "197">
                <tr>
                    <td style="text-align: left;">Angebots-Nr.</td>
                    <td style="text-align: right;">' . $offercontent->number . '</td>
                </tr>
            </table>';

        $offerinfo = '
            <table cellpadding="0.5" cellspacing="0" width = "197">
                <tr>
                    <td style="text-align: left;">Angebotsdatum</td>
                    <td style="text-align: right;">'.$formattedDate.'</td>
                </tr>
                <tr>
                    <td style="text-align: left;">Gültigkeitsdauer</td>
                    <td style="text-align: right;">3 Wochen</td>
                </tr>
            </table>';

        $footer = $client->companyname.", ".$client->address.", ".$client->postalcode." ".$client->location.", Tel.: ".$client->phone.", E-Mail: ".$client->email.", \n".$client->regional_court.", FN-Nr.: ".$client->company_registration_number.", USt.-ID: ".$client->vat_number.", Steuer-Nr.: ".$client->tax_number.", \nGeschäftsführung: ".$client->management.", Bank: ".$client->bank.", IBAN: ".$client->accountnumber.", BIC: ".$client->bic;

        $positiontableheader = '
            <table cellpadding="2" cellspacing="0" width = "533" style=" background-color: '.$client->color.';">
                <tr>
                    <td style="text-align: left; width: 9%; color: '.$color.'; font-family: segoebd;">Menge</td>
                    <td style="text-align: left; width: 9%; color: '.$color.'; font-family: segoebd;">Einheit</td>
                    <td style="text-align: left; width: 52%; color: '.$color.'; font-family: segoebd;">Bezeichnung</td>
                    <td style="text-align: right; width: 15%; color: '.$color.'; font-family: segoebd;">Einzelpreis</td>
                    <td style="text-align: right; width: 15%; color: '.$color.'; font-family: segoebd;">Betrag</td>
                </tr>
            </table>';

        $positiontablebody = '<table cellpadding="2" cellspacing="0" width = "533" >'; //style="border: 0.5px solid black;"



        foreach ($positions as $position) {
            $positiontablebody .= '<tr><td></td></tr>';
            $details = nl2br($position->details) ?? '';
            if($position->positiontext == 1) {
                $positiontablebody .= '

                    <tr>
                        <td style="text-align:center; width: 100%; white-space: pre-line;"><b>'.$details.'</b></td>
                    </tr>
                ';
            } else {
                $positiontablebody .= '

                    <tr>
                        <td style="text-align: left; width: 9%;">'.number_format($position->amount, 2, ',', '') .'</td>
                        <td style="text-align: left; width: 9%;">'.$position->unitdesignation.'</td>
                        <td style="text-align: left; width: 52%;">'.$position->designation.'</td>
                        <td style="text-align: right; width: 15%;">'.number_format($position->price, 2, ',', '') .' EUR</td>
                        <td style="text-align: right; width: 15%;">'.number_format(($position->price * $position->amount), 2, ',', '') .' EUR</td>
                    </tr>
                    <tr>
                        <td style="width: 15%;"></td>

                        <td style="text-align: left; width: 70%; white-space: pre-line;">'.$details.'</td>
                        <td style="width: 15%;"></td>
                    </tr>
                ';
            }

        }


        $positiontablebody .= '</table>';
        //dd($positiontablebody);

        $positionsum = '
            <table cellpadding="2" cellspacing="0.5" width = "533" style="border-top: 0.5px solid black;">
                <tr>
                    <td style="text-align: left; width: 18%;"></td>
                    <td style="text-align: left; width: 67%; color: '.$client->color.';">Gesamtbetrag netto</td>
                    <td style="text-align: right; width: 15%; color: '.$client->color.';">'.number_format($totalSum, 2, ',', '').' EUR</td>
                </tr>
                <tr>
                    <td style="text-align: left; width: 18%;"></td>
                    <td style="text-align: left; width: 67%;">zzgl. Umsatzsteuer '.$offercontent->taxrate .'%</td>
                    <td style="text-align: right; width: 15%;">'.number_format($totalSum*$offercontent->taxrate/100, 2, ',', '').' EUR</td>
                </tr>
                <tr>
                    <td style="text-align: left; width: 18%;"></td>
                    <td style="text-align: left; width: 67%; color: '.$client->color.'; font-family: segoebd; font-weight: bold;">Gesamtbetrag brutto</td>
                    <td style="text-align: right; width: 15%; color: '.$client->color.'; font-family: segoebd; font-weight: bold;">'.number_format($totalSum*($offercontent->taxrate/100+1), 2, ',', '').' EUR</td>
                </tr>
            </table>';


        // PDF erstellen
        $pdf = new MyPDF();

        $pdf->setCustomFooterText($footer);

        $pdf->AddPage();
        
        // Definiere Seitennummern nach dem Hinzufügen der ersten Seite
        $pageNumber = $pdf->PageNo();
        $totalPages = $pdf->getNumPages();

        $pageinfo = '
            <table cellpadding="0.5" cellspacing="0" width = "70">
                <tr>
                    <td style="text-align: left;">Seite</td>
                    <td style="text-align: right;">'.$pageNumber.' von '.$totalPages.'</td>
                </tr>
            </table>';

        $pdf->SetFont('arial', '', 10);
        if ($localImagePath && file_exists($localImagePath)) {
            try {
                // Erstelle ein temporäres Verzeichnis, falls es nicht existiert
                $tempDir = storage_path('app/temp');
                if (!file_exists($tempDir)) {
                    mkdir($tempDir, 0755, true);
                }

                // Verarbeite das Bild mit Intervention/Image
                $image = Image::make($localImagePath);
                
                // Konvertiere zu einem anderen Format (z.B. GIF)
                $tempImagePath = $tempDir . '/temp_' . time() . '.gif';
                $image->encode('gif')->save($tempImagePath);
                
                // Füge das Bild zum PDF hinzu
                $pdf->Image($tempImagePath, 18, 12, 0, 30);
                
                // Lösche temporäre Datei
                if (file_exists($tempImagePath)) {
                    unlink($tempImagePath);
                }
            } catch (\Exception $e) {
                \Log::error('Fehler bei der Bildverarbeitung: ' . $e->getMessage());
                // Fallback: Versuche das Original-Bild zu verwenden
                if (file_exists($localImagePath)) {
                    $pdf->Image($localImagePath, 18, 12, 0, 30);
                }
            }
        }
        $pdf->SetCreator('Venditio');
        $pdf->SetAuthor('Venditio');
        $pdf->SetTitle('Angebot' . ' ' . $offercontent->number);
        $pdf->SetMargins(15, 15, 15);

        $pdf->SetFont($fontbold, '', 12);
        $pdf->SetXY(128, 44);
        $pdf->writeHTML($offerNumber, true, true, false, true, 'R');
        
        $pdf->SetXY(128, 51);
        $pdf->SetFont($font, '', 10);
        $pdf->writeHTML($offerinfo, true, true, false, true, 'R');

        //Eigene Daten über den Kunden
        $pdf->SetXY(10, 50);
        $pdf->SetFont('arial', '', 7);
        $pdf->writeHTML($clientdata, true, true, false, true, 'R');
        
        $pdf->SetFont($font, '', 10);
        $pdf->SetXY(10, 55);
        $pdf->writeHTML($customerdata, true, true, false, true, 'R');
        
        $pdf->SetXY(128, 61);
        $pdf->SetFont($font, '', 10);
        $pdf->writeHTML($operation, true, true, false, true, 'R');
        
        $pdf->SetXY(172, 75);
        $pdf->SetFont($font, '', 10);
        $pdf->writeHTML($pageinfo, true, true, false, true, 'R');

        $pdf->SetXY(15, 90);
        $pdf->SetFont($font, 'B', 20);
        $pdf->Cell(100, 10, 'Angebot ' . $offercontent->number, 0, 1, 'L');
        
        
        
        
        $pdf->SetXY(10, 105);
        $pdf->SetFont($font, '', 10);
        $pdf->Cell(100, 10, 'Unter Einhaltung unserer allg. Geschäftsbediengungen, erlauben wir uns, Ihnen folgendes Angebot zu unterbreiten:', 0, 1, 'L');
        
        $pdf->SetXY(10, 115);
        $pdf->writeHTML($positiontableheader, true, true, false, true, 'R');
        
        $pdf->SetXY(10, 120);
        $pdf->writeHTML($positiontablebody, true, true, false, true, 'R');

        $pdf->writeHTML($positionsum, true, true, false, true, 'R');
        $pdf->writeHTML('<br><br>Bei Annahme des Angebots bitten wir um Unterfertigung<br><br><br><br>_____________________________________________<br>   Unterschrift Kunde', true, true, true, true, 'L');


        //dd($preview);

        // Definiere den Modus als Variable (z. B. 'I', 'D', 'F', 'S')
        $outputMode = $preview ?? 'I'; // Standardmodus ist 'I', falls $preview nicht gesetzt ist

        // Für den Modus 'F' (Speichern als Datei) ist ein Speicherpfad erforderlich
        if ($outputMode === 'F') {
            $filePath = storage_path('pdfs/Offrer/Angebot_' . $offercontent->number . '.pdf');
            $pdf->Output($filePath, 'F');
            return response()->download($filePath)
                ->header('Content-Type', 'application/pdf');
        }

        // Für andere Modi (Inline, Download, String)
        $pdfContent = $pdf->Output('Angebot_' . $offercontent->number . '.pdf', $outputMode);

        // Rückgabe der Response
        return response($pdfContent)
            ->header('Content-Type', 'application/pdf');


    }

    public function createInvoicePdf(Request $request)
    {

        $font = 'arial';
        $fontbold = 'segoebd';
        

        $user = Auth::user();
        $clientId = $user->client_id;

        $client = Clients::where('id', $clientId)->firstOrFail();

        if ($client->color <> '#000000') {
            $color = 'white';
        } else {
            $color = 'black';
        }

        $objectId = $request->input('invoice_id');
        $objectType = $request->input('objecttype'); // "offer" oder "invoice"
        $preview = $request->input('prev', 0); // 0: Download, 1: Vorschau, 2: Speichern
        //dd($objectId);
        //dd($request->all());
        $invoice = Invoices::from('invoices as i')
            ->where('i.id', $objectId)
            ->join('customers as c', 'i.customer_id', '=', 'c.id')
            ->where('c.client_id', $clientId)
            ->first();

        if (!$invoice) {
            abort(403, 'Sie sind nicht berechtigt diese Rechnung zu sehen!');
        }

        $invoicecontent = Invoices::join('taxrates','invoices.tax_id','=','taxrates.id')
            ->where('invoices.id', $objectId)
            ->first(['invoices.*','taxrates.*']);

        //dd($invoicecontent->depositamount);

        $positions = Invoicepositions::where('invoice_id', $objectId)
            ->join('units','invoicepositions.unit_id','=','units.id')
            ->orderBy('sequence')
            ->get(['invoicepositions.*','units.*']);
        //dd($positions);

        $totalSum = Invoicepositions::where('invoice_id', $objectId)
            ->join('units', 'invoicepositions.unit_id', '=', 'units.id')
            ->sum(DB::raw('invoicepositions.amount * invoicepositions.price'));

        $condition = Condition::join('invoices','invoices.condition_id','=','conditions.id')
            ->where('invoices.id', $objectId)
            ->first('conditions.*');


        

        $customer = Customer::join('invoices','customers.id','=','invoices.customer_id')
            ->where('invoices.id','=',$objectId)
            ->first('customers.*');

        // Logo-Pfad aus der clients-Tabelle
        $localImagePath = $client->logo ? storage_path('app/public/logos/' . $client->logo) : null;
        //dd($localImagePath);
        $imageHeight = $client->logoheight;
        $imageWidth = $client->logowidth;

        //dd($client);
        $clientdata = '
            <table cellpadding="2" cellspacing="0" width = "300">
                <tr>
                    <td style="text-align: left; color: black;">'.$client->companyname.' - '.$client->address.' - '.$client->postalcode.' '.$client->location.'</td>
                </tr>
            </table>';
        
        $customerdata = '
            <table cellpadding="0" cellspacing="0" style="width:100%;">
                <tr>
                    <td style="text-align: left;">' . ($customer->companyname ?? '') . '</td>
                </tr>
                <tr>
                    <td style="text-align: left;">' . ($customer->customername ?? '') . '</td>
                </tr>
                <tr>
                    <td style="text-align: left;">' . ($customer->address ?? '') . '</td>
                </tr>
                <tr>
                    <td style="text-align: left;">' . ($customer->postalcode ?? '') . ' ' . ($customer->location ?? '') . '</td>
                </tr>
                <tr>
                    <td style="text-align: left;">' . ($customer->country ?? '') . '</td>
                </tr>
            </table>';
        $clienttable = '
            <table cellpadding="2" cellspacing="0" width = "222" style=" background-color: rgb(243, 243, 243);">
                <tr>
                    <td style="text-align: right;">'.$client->companyname.'</td>
                </tr>
                <tr>
                    <td style="text-align: right;">Registrierzahl: </td>
                </tr>
                <tr>
                    <td style="text-align: right;">Geschäftsform: '.$client->business.'</td>
                </tr>
                <tr>
                    <td style="text-align: right;">'.$client->address.'</td>
                </tr>
                <tr>
                    <td style="text-align: right;">'.$client->postalcode." ".$client->location.'</td>
                </tr>
                <tr>
                    <td style="text-align: right;">: '.$client->email.'</td>
                </tr>
                <tr>
                    <td style="text-align: right;">Tel: '.$client->phone.'</td>
                </tr>
            </table>';


            if ($invoicecontent) {
                $formattedPeriodFrom = $invoicecontent->periodfrom
                    ? \Carbon\Carbon::parse($invoicecontent->periodfrom)->format('d.m.Y')
                    : '';

                $formattedPeriodTo = $invoicecontent->periodto
                    ? \Carbon\Carbon::parse($invoicecontent->periodto)->format('d.m.Y')
                    : '';
            } else {
                $formattedPeriodFrom = '';
                $formattedPeriodTo = '';
            }

            $operation = '
            <table cellpadding="0.5" width="197" style="border: 0.5px solid white; width: 100%;">
                
                <tr>
                    <td style="text-align: left;">Zahlungskonditionen:</td>
                    <td style="text-align: right;">' . ($condition->conditionname ?? '') . '</td>
                </tr>
                <tr>
                    <td style="text-align: left;">Ihre Kundennummer:</td>
                    <td style="text-align: right;">' . ($customer->customernumber ?? '') . '</td>
                </tr>
                <tr>
                    <td style="text-align: left;">Ihre USt-ID:</td>
                    <td style="text-align: right;">' . ($customer->vat_number ?? '') . '</td>
                </tr>
            </table>';



        $formattedDate = $invoicecontent && $invoicecontent->date
            ? \Carbon\Carbon::parse($invoicecontent->date)->format('d.m.Y')
            : '';



        

        $positiontableheader = '
            <table cellpadding="2" cellspacing="0" width = "533" style=" background-color: '.$client->color.';">
                <tr>
                    <td style="text-align: left; width: 9%; color: '.$color.'; font-family: segoebd;">Menge</td>
                    <td style="text-align: left; width: 9%; color: '.$color.'; font-family: segoebd;">Einheit</td>
                    <td style="text-align: left; width: 52%; color: '.$color.'; font-family: segoebd;">Bezeichnung</td>
                    <td style="text-align: right; width: 15%; color: '.$color.'; font-family: segoebd;">Einzelpreis</td>
                    <td style="text-align: right; width: 15%; color: '.$color.'; font-family: segoebd;">Betrag</td>
                </tr>
            </table>';

        $positiontablebody = '<table cellpadding="2" cellspacing="0" width = "533" >'; //style="border: 0.5px solid black;"



        foreach ($positions as $position) {
            $positiontablebody .= '<tr><td></td></tr>';
            $details = nl2br($position->details) ?? '';
            if($position->positiontext == 1) {

                $positiontablebody .= '

                    <tr>
                        <td style="text-align:center; width: 100%; white-space: pre-line;"><b>'.$details.'</b></td>
                    </tr>
                ';
            } else {
                $positiontablebody .= '
                    <tr>
                        <td style="text-align: left; width: 9%;">'.number_format($position->amount, 2, ',', '') .'</td>
                        <td style="text-align: left; width: 9%;">'.$position->unitdesignation.'</td>
                        <td style="text-align: left; width: 52%;">'.$position->designation.'</td>
                        <td style="text-align: right; width: 15%;">'.number_format($position->price, 2, ',', '') .' EUR</td>
                        <td style="text-align: right; width: 15%;">'.number_format(($position->price * $position->amount), 2, ',', '') .' EUR</td>
                    </tr>
                    <tr>
                        <td style="width: 15%;"></td>
                        <td style="text-align: left; width: 70%; white-space: pre-line;">'.$details.'</td>
                        <td style="width: 15%;"></td>
                    </tr>
                ';
            }
        }


        $positiontablebody .= '</table>';
        //dd($positiontablebody);


        $positionsum = '
            <table cellpadding="2" cellspacing="0" width = "533" style="border-top: 0.1px solid black;">
                <tr>
                    <td style="text-align: left; width: 70%;"></td>
                    <td style="text-align: left; width: 15%;">Netto:</td>
                    <td style="text-align: right; width: 15%;">'.number_format($totalSum, 2, ',', '').' EUR</td>
                </tr>
                <tr>
                    <td style="text-align: left; width: 70%;"></td>
                    <td style="text-align: left; width: 15%; border-bottom: 0.5px solid black;">'.$invoicecontent->taxrate .'% Ust:</td>
                    <td style="text-align: right; width: 15%; border-bottom: 0.5px solid black;">'.number_format($totalSum*$invoicecontent->taxrate/100, 2, ',', '').' EUR</td>
                </tr>
                <tr>
                    <td style="text-align: left; width: 70%;"></td>
                    <td style="text-align: left; width: 15%; border-bottom: 1px solid black;">Brutto:</td>
                    <td style="text-align: right; width: 15%; border-bottom: 1px solid black;">'.number_format($totalSum*($invoicecontent->taxrate/100+1), 2, ',', '').' EUR</td>
                </tr>
            </table>';


            $footer = $client->companyname.", ".$client->address.", ".$client->postalcode." ".$client->location.", Tel.: ".$client->phone.", E-Mail: ".$client->email.", \n".$client->regional_court.", FN-Nr.: ".$client->company_registration_number.", USt.-ID: ".$client->vat_number.", Steuer-Nr.: ".$client->tax_number.", \nGeschäftsführung: ".$client->management.", Bank: ".$client->bank.", IBAN: ".$client->accountnumber.", BIC: ".$client->bic;


            if ($invoicecontent->depositamount > 0) {
                $positionsum .= '
                    <table cellpadding="2" cellspacing="0" width = "533">
                        <tr>
                            <td style="text-align: left; width: 70%;"></td>
                            <td style="text-align: left; width: 15%; border-bottom: 0.5px solid black;">Anzahlung</td>
                            <td style="text-align: right; width: 15%; border-bottom: 0.5px solid black;">-'.number_format($invoicecontent->depositamount, 2, ',', '').' EUR</td>
                        </tr>
                        <tr>
                            <td style="text-align: left; width: 70%;"></td>
                            <td style="text-align: left; width: 15%; border-bottom: 2px solid black;">Zu zahlen:</td>
                            <td style="text-align: right; width: 15%; border-bottom: 2px solid black;">'.number_format($totalSum*($invoicecontent->taxrate/100+1)-$invoicecontent->depositamount, 2, ',', '').' EUR</td>
                        </tr>
                    </table>';
            }

        //$html="test";
        // PDF erstellen
        //define('K_PATH_FONTS', resource_path('fonts/') . '/');
    
        $pdf = new MyPDF();

        $pdf->AddFont('nunitosans', 'B', 'nunitosans.php');
        $pdf->SetFont('nunitosans', 'B', 10); // Normalschnitt

        $pdf->setCustomFooterText($footer);

        $pdf->AddPage();
        $pageNumber = $pdf->PageNo();
        $totalPages = $pdf->getNumPages();
        $pdf->SetFont('nunitosans', '', 9);
        
        if ($localImagePath && file_exists($localImagePath)) {
            try {
                // Erstelle ein temporäres Verzeichnis, falls es nicht existiert
                $tempDir = storage_path('app/temp');
                if (!file_exists($tempDir)) {
                    mkdir($tempDir, 0755, true);
                }

                // Verarbeite das Bild mit Intervention/Image
                $image = Image::make($localImagePath);
                
                // Konvertiere zu einem anderen Format (z.B. GIF)
                $tempImagePath = $tempDir . '/temp_' . time() . '.gif';
                $image->encode('gif')->save($tempImagePath);
                
                // Füge das Bild zum PDF hinzu
                $pdf->Image($tempImagePath, 18, 12, 0, 30);
                
                // Lösche temporäre Datei
                if (file_exists($tempImagePath)) {
                    unlink($tempImagePath);
                }
            } catch (\Exception $e) {
                \Log::error('Fehler bei der Bildverarbeitung: ' . $e->getMessage());
                // Fallback: Versuche das Original-Bild zu verwenden
                if (file_exists($localImagePath)) {
                    $pdf->Image($localImagePath, 18, 12,  0, 30);
                }
            }
        }

        $invoiceinfo = '
            <table cellpadding="0.5" cellspacing="0" width = "197">
                <tr>
                    <td style="text-align: left;">Rechnungsdatum</td>
                    <td style="text-align: right;">'.$formattedDate.'</td>
                </tr>
                <tr>
                    <td style="text-align: left;">Leistungszeitraum</td>
                    <td style="text-align: right;">' . ($formattedPeriodFrom ?? '') . ' - ' . ($formattedPeriodTo ?? '') . '</td>
                </tr>
                
            </table>';

        $pageinfo = '
            <table cellpadding="0.5" cellspacing="0" width = "70">
                <tr>
                    <td style="text-align: left;">Seite</td>
                    <td style="text-align: right;">'.$pageNumber.' von '.$totalPages.'</td>
                </tr>
            </table>';

        $invoiceNumber = '
            <table cellpadding="0.5" cellspacing="0" width = "197">
                <tr>
                    <td style="text-align: left;">Rechnungs-Nr.</td>
                    <td style="text-align: right;">' . $invoicecontent->number . '</td>
                </tr>
            </table>';

        $invoicetext = '
            <table cellpadding="2" cellspacing="0" width = "533">
                <tr>
                    <td style="text-align: left;">Vielen Dank für Ihren Auftrag und das damit verbundene Vertrauen! Hiermit stellen wir Ihnen die folgenden Leistungen in Rechnung:</td>
                </tr>
            </table>';

        $biginvoicenumber = '
            <table cellpadding="2" cellspacing="0" width = "533">
                <tr>
                    <td style="text-align: left; color: '.$client->color.';">Rechnung Nr. ' . $invoicecontent->number . '</td>
                </tr>
            </table>';

        $pdf->SetCreator('Venditio');
        $pdf->SetAuthor('{{$client->name}}');
        
        $pdf->SetTitle('Rechnung' . ' ' . $invoicecontent->number);
        $pdf->SetMargins(15, 15, 15);
        
        //Eigene Daten über den Kunden
        $pdf->SetXY(10, 50);
        $pdf->SetFont($font, '', 7);
        $pdf->writeHTML($clientdata, true, true, false, true, 'R');
        
        //Kundendaten
        $pdf->SetXY(10, 55);
        $pdf->SetFont($font, '', 10);
        $pdf->writeHTML($customerdata, true, true, false, true, 'R');

        //Rechnungsnummer klein rechts
        //$pdf->SetXY(128, 12);
        //$pdf->writeHTML($clienttable, true, true, false, true, 'R');
        $pdf->SetFont($fontbold, '', 10);
        $pdf->SetXY(128, 44);
        $pdf->writeHTML($invoiceNumber, true, true, false, true, 'R');
        
        //Rechnungsdatum und Leistungszeitraum
        $pdf->SetXY(128, 51);
        $pdf->SetFont($font, '', 10);
        $pdf->writeHTML($invoiceinfo, true, true, false, true, 'R');
        
        //Zahlungskonditionen, Kundennummer, USt-ID
        $pdf->SetXY(128, 61);
        $pdf->SetFont($font, '', 10);
        $pdf->writeHTML($operation, true, true, false, true, 'R');
        
        //Seite
        $pdf->SetXY(172, 75);
        $pdf->SetFont($font, '', 10);
        $pdf->writeHTML($pageinfo, true, true, false, true, 'R');

        //Rechnungsnummer groß links
        $pdf->SetXY(10, 90);
        $pdf->SetFont($font, 'B', 20);
        $pdf->writeHTML($biginvoicenumber, true, true, false, true, 'R');

        //$pdf->Cell(100, 10, 'Rechnung Nr. ' . $invoicecontent->number, 0, 1, 'L');
        

        $pdf->SetXY(10, 105);
        $pdf->SetFont($font, '', 9.5);
        $pdf->writeHTML($invoicetext, true, true, false, true, 'R');
        
        //$pdf->multiCell(190, 10, $invoicetext, 0, 'L', 0, 1);
        
        $pdf->SetXY(10, 115);
        $pdf->SetFont($fontbold, '', 9.5);
        $pdf->writeHTML($positiontableheader, true, true, false, true, 'R');
        
        $pdf->SetXY(10, 120);
        $pdf->SetFont($font, '', 9.5);
        $pdf->writeHTML($positiontablebody, true, true, false, true, 'R');

        $pdf->writeHTML($positionsum, true, true, false, true, 'R');
        if ($client->smallbusiness) {
            $pdf->writeHTML('<br><br>Kleinunternehmer gem. § 6 Abs. 1 Z 27 UStG', true, true, true, true, 'L');
        }



        $outputMode = $preview ?? 'I'; // Standardmodus ist 'I', falls $preview nicht gesetzt ist

        // Für den Modus 'F' (Speichern als Datei) ist ein Speicherpfad erforderlich
        if ($outputMode === 'F') {
            $filePath = storage_path('pdfs/Invoices/Rechnung_' . $invoicecontent->number . '.pdf');
            $pdf->Output($filePath, 'F');
            return response()->download($filePath)
                ->header('Content-Type', 'application/pdf');
        }

        // Für andere Modi (Inline, Download, String)
        $pdfContent = $pdf->Output('Rechnung_' . $invoicecontent->number . '.pdf', $outputMode);

        // Rückgabe der Response
        return response($pdfContent)
            ->header('Content-Type', 'application/pdf');

    }



public function sendInvoiceByEmail(Request $request)
{
    // Validierung der Eingabedaten
    $request->validate([
        'invoice_id' => 'required|integer|exists:invoices,id',
        'email' => 'required|email',
        'subject' => 'required|string',
        'copy_email' => 'nullable|email',
        'message' => 'required|string',
    ]);

    // Abrufen der Rechnungsdaten mit Joins
    $invoiceData = Invoices::join('customers', 'customers.id', '=', 'invoices.customer_id')
        ->join('clients', 'clients.id', '=', 'customers.client_id')
        ->where('invoices.id', '=', $request->invoice_id)
        ->select(
            'customers.*',
            'invoices.*',
            'customers.id as customer_id',
            'clients.email as senderemail',
            'clients.companyname as clientname',
            'invoices.number as invoice_number'
        )
        ->first();

    if (!$invoiceData || !$invoiceData->senderemail) {
        return response()->json(['message' => 'Client oder Absender-E-Mail nicht gefunden.'], 404);
    }

    // Zuweisen der Variablen aus dem Request und Datenbankabfrage
    $email = $request->input('email');
    $subject = $request->input('subject');
    $senderEmail = $invoiceData->senderemail;
    $messageBody = $request->input('message');
    $senderName = $invoiceData->clientname;
    $invoice_number = $invoiceData->invoice_number;

    // PDF generieren
    $request->merge(['prev' => 'S']);
    $pdfResponse = $this->createInvoicePdf($request);
    $pdfContent = $pdfResponse->getContent();

    // Generiere einen zufälligen Dateinamen für die Speicherung
    $randomFileName = Str::random(40) . '.pdf';

    // Definiere das Verzeichnis und Datei-Pfad
    $storagePath = storage_path('app/objects');

    // Sicherstellen, dass das Verzeichnis existiert
    if (!file_exists($storagePath)) {
        mkdir($storagePath, 0755, true);
    }

    // Vollständigen Dateipfad mit zufälligem Namen
    $filePath = $storagePath . '/' . $randomFileName;

    // Speichern der PDF-Datei unter dem zufälligen Namen
    try {
        file_put_contents($filePath, $pdfContent);
    } catch (\Exception $e) {
        \Log::error('Fehler beim Speichern der PDF: ' . $e->getMessage());
        return response()->json(['message' => 'Fehler beim Speichern der Rechnung.'], 500);
    }

    $sentDate = now();
    $status = false;

    try {
        $ccEmail = $request->input('copy_email');

        Mail::send([], [], function ($message) use (
            $randomFileName,
            $invoice_number,
            $email,
            $subject,
            $messageBody,
            $filePath,
            $senderEmail,
            $senderName,
            $ccEmail
        ) {
            $message->from($senderEmail, $senderName)
                    ->to($email)
                    ->subject($subject)
                    ->html($messageBody)
                    ->attach($filePath, [
                        // Verwende als Alias den lesbaren Namen für den Empfänger
                        'as' => 'Rechnung_' . $invoice_number . '.pdf',
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

    // Speichere den zufälligen Dateinamen in der Datenbank
    OutgoingEmail::create([
        'type' => 1,
        'customer_id' => $invoiceData->customer_id,
        'objectnumber' => $invoiceData->number,
        'sentdate' => $sentDate,
        'getteremail' => $email,
        'filename' => $randomFileName,  // Speicher den zufälligen Dateinamen
        'withattachment' => true,
        'status' => $status,
        'client_id' => $request->user()->client_id,
    ]);

    return redirect()->route('outgoingemails.index')->with('success', 'Rechnung wurde erfolgreich per E-Mail versendet.');
}

public function sendOfferByEmail(Request $request)
{
    // Validierung der Eingabedaten
    $request->validate([
        'offer_id' => 'required|integer|exists:offers,id',
        'email' => 'required|email',
        'subject' => 'required|string',
        'copy_email' => 'nullable|email',
        'message' => 'required|string',
    ]);

    // Abrufen der Rechnungsdaten mit Joins
    $offerData = Offers::join('customers', 'customers.id', '=', 'offers.customer_id')
        ->join('clients', 'clients.id', '=', 'customers.client_id')
        ->where('offers.id', '=', $request->offer_id)
        ->select(
            'customers.*',
            'offers.*',
            'customers.id as customer_id',
            'clients.email as senderemail',
            'clients.companyname as clientname',
            'offers.number as offer_number'
        )
        ->first();

    if (!$offerData || !$offerData->senderemail) {
        return response()->json(['message' => 'Client oder Absender-E-Mail nicht gefunden.'], 404);
    }

    // Zuweisen der Variablen aus dem Request und Datenbankabfrage
    $email = $request->input('email');
    $subject = $request->input('subject');
    $senderEmail = $offerData->senderemail;
    $messageBody = $request->input('message');
    $senderName = $offerData->clientname;
    $offer_number = $offerData->offer_number;

    // PDF generieren
    $request->merge(['prev' => 'S']);
    $pdfResponse = $this->createOfferPdf($request);
    $pdfContent = $pdfResponse->getContent();

    // Generiere einen zufälligen Dateinamen für die Speicherung
    $randomFileName = Str::random(40) . '.pdf';

    // Definiere das Verzeichnis und Datei-Pfad
    $storagePath = storage_path('app/objects');

    // Sicherstellen, dass das Verzeichnis existiert
    if (!file_exists($storagePath)) {
        mkdir($storagePath, 0755, true);
    }

    // Vollständigen Dateipfad mit zufälligem Namen
    $filePath = $storagePath . '/' . $randomFileName;

    // Speichern der PDF-Datei unter dem zufälligen Namen
    try {
        file_put_contents($filePath, $pdfContent);
    } catch (\Exception $e) {
        \Log::error('Fehler beim Speichern der PDF: ' . $e->getMessage());
        return response()->json(['message' => 'Fehler beim Speichern der Rechnung.'], 500);
    }

    $sentDate = now();
    $status = false;

    try {
        $ccEmail = $request->input('copy_email');

        Mail::send([], [], function ($message) use (
            $randomFileName,
            $offer_number,
            $email,
            $subject,
            $messageBody,
            $filePath,
            $senderEmail,
            $senderName,
            $ccEmail
        ) {
            $message->from($senderEmail, $senderName)
                    ->to($email)
                    ->subject($subject)
                    ->html($messageBody)
                    ->attach($filePath, [
                        // Verwende als Alias den lesbaren Namen für den Empfänger
                        'as' => 'Angebot_' . $offer_number . '.pdf',
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

    // Speichere den zufälligen Dateinamen in der Datenbank
    OutgoingEmail::create([
        'type' => 2,
        'customer_id' => $offerData->customer_id,
        'objectnumber' => $offerData->number,
        'sentdate' => $sentDate,
        'getteremail' => $email,
        'filename' => $randomFileName,  // Speicher den zufälligen Dateinamen
        'withattachment' => true,
        'status' => $status,
        'client_id' => $request->user()->client_id,
    ]);

    return redirect()->route('outgoingemails.index')->with('success', 'Angebot wurde erfolgreich per E-Mail versendet.');
}




}
