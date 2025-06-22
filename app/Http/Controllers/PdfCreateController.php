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
            <table cellpadding="0" cellspacing="0" width="100%" style="font-size: 8px; color: grey">
                <tr>
                    <td width="25%" style="vertical-align: top;">
                        <table cellpadding="0.5" cellspacing="1" width="100%" style="font-size: 8px; color: grey">
                            ' . implode('', $addressRows) . '
                        </table>
                    </td>
                    <td width="25%" style="vertical-align: top;">
                        <table cellpadding="0.5" cellspacing="1" width="100%" style="font-size: 8px; color: grey">
                            ' . implode('', $contactRows) . '
                        </table>
                    </td>
                    <td width="25%" style="vertical-align: top;">
                        <table cellpadding="0.5" cellspacing="1" width="100%" style="font-size: 8px; color: grey">
                            ' . implode('', $legalRows) . '
                        </table>
                    </td>
                    <td width="25%" style="vertical-align: top;">
                        <table cellpadding="0.5" cellspacing="1" width="100%" style="font-size: 8px; color: grey">
                            ' . implode('', $bankRows) . '
                        </table>
                    </td>
                </tr>
            </table>';

        return $footer;
    }

    /**
     * Verarbeitet und fügt Logo zum PDF hinzu
     */
    private function processLogo($pdf, $localImagePath)
    {
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
    }

    /**
     * Formatiert Datum mit Carbon
     */
    private function formatDate($date)
    {
        return $date ? \Carbon\Carbon::parse($date)->format('d.m.Y') : '';
    }

    /**
     * Generiert Kundendaten-Tabelle
     */
    private function generateCustomerData($customer)
    {
        return '
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
    }

    /**
     * Generiert Positionstabellen-Header
     */
    private function generatePositionTableHeader($client, $color, $type = 'offer')
    {
        $lastColumnText = $type === 'invoice' ? 'Gesamtpreis' : 'Betrag';
        
        return '
            <table cellpadding="2" cellspacing="0" width = "100%" style=" background-color: '.$client->color.';">
                <tr>
                    <td style="text-align: left; width: 7%; color: '.$color.'; font-family: segoebd;">Pos.</td>
                    <td style="text-align: left; width: 54%; color: '.$color.'; font-family: segoebd;">Bezeichnung</td>
                    <td style="text-align: left; width: 12%; color: '.$color.'; font-family: segoebd;">Menge</td>
                    <td style="text-align: right; width: 12%; color: '.$color.'; font-family: segoebd;">Einzelpreis</td>
                    <td style="text-align: right; width: 15%; color: '.$color.'; font-family: segoebd;">'.$lastColumnText.'</td>
                </tr>
            </table>';
    }

    /**
     * Generiert Positionstabellen-Body
     */
    private function generatePositionTableBody($positions)
    {
        $positiontablebody = '<table cellpadding="2" cellspacing="0" width = "100%" >';
        $positionNumber = 1;

        foreach ($positions as $position) {
            $positiontablebody .= '<tr><td></td></tr>';
            $details = nl2br($position->details) ?? '';
            
            if($position->positiontext == 1) {
                $positiontablebody .= '
                    <tr>
                        <td style="text-align:center; width: 100%; white-space: pre-line;"><b>'.$details.'</b></td>
                    </tr>';
            } else {
                $positiontablebody .= '
                    <tr>
                        <td style="text-align: center; width: 7%;">'.$positionNumber.'.</td>
                        <td style="text-align: left; width: 54%;">'.$position->designation.'</td>
                        <td style="text-align: left; width: 12%;">'.number_format($position->amount, 2, ',', '') .' '.$position->unitdesignation.'</td>
                        <td style="text-align: right; width: 12%;">'.number_format($position->price, 2, ',', '') .' EUR</td>
                        <td style="text-align: right; width: 15%;">'.number_format(($position->price * $position->amount), 2, ',', '') .' EUR</td>
                    </tr>
                    <tr>
                        <td style="width: 7%;"></td>
                        <td style="text-align: left; width: 54%; white-space: pre-line;">'.$details.'</td>
                        <td style="width: 12%;"></td>
                        <td style="width: 12%;"></td>
                        <td style="width: 15%;"></td>
                    </tr>';
                $positionNumber++;
            }
        }

        $positiontablebody .= '</table>';
        return $positiontablebody;
    }

    /**
     * Generiert Positionssummen-Tabelle
     */
    private function generatePositionSum($totalSum, $taxRate, $client, $type = 'offer', $reverseCharge = false)
    {
        $leftWidth = $type === 'invoice' ? '7%' : '18%';
        $middleWidth = $type === 'invoice' ? '78%' : '67%';
        
        $html = '
            <table cellpadding="2" cellspacing="0" width = "100%" style="border-top: 0.5px solid '.$client->color.';">
                <tr>
                    <td style="text-align: left; width: '.$leftWidth.';"></td>
                    <td style="text-align: left; width: '.$middleWidth.'; color: '.$client->color.';">Gesamtbetrag netto</td>
                    <td style="text-align: right; width: 15%; color: '.$client->color.';">'.number_format($totalSum, 2, ',', '').' EUR</td>
                </tr>';
        
        $html .= '
            <tr>
                <td style="text-align: left; width: '.$leftWidth.';"></td>
                <td style="text-align: left; width: '.$middleWidth.';">zzgl. Umsatzsteuer '.($reverseCharge ? '0' : $taxRate) .'%</td>
                <td style="text-align: right; width: 15%;">'.number_format($reverseCharge ? 0 : $totalSum*$taxRate/100, 2, ',', '').' EUR</td>
            </tr>';
        

        
        $html .= '
                <tr>
                    <td style="text-align: left; width: '.$leftWidth.';"></td>
                    <td style="text-align: left; width: '.$middleWidth.'; color: '.$client->color.'; font-family: segoebd; font-weight: bold;'.($type === 'invoice' ? ' font-size: 11px;' : '').'">Gesamtbetrag brutto</td>
                    <td style="text-align: right; width: 15%; color: '.$client->color.'; font-family: segoebd; font-weight: bold;'.($type === 'invoice' ? ' font-size: 11px;' : '').'">'.number_format($reverseCharge ? $totalSum : $totalSum*($taxRate/100+1), 2, ',', '').' EUR</td>
                </tr>
            </table>';
            
        return $html;
    }

    /**
     * Generiert generische E-Mail-Versand-Logik
     */
    private function sendDocumentByEmail($request, $documentType)
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

        // Variablen zuweisen
        $email = $request->input('email');
        $subject = $request->input('subject');
        $senderEmail = $documentData->senderemail;
        $messageBody = $request->input('message');
        $senderName = $documentData->clientname;
        $documentNumber = $documentData->document_number;

        // PDF generieren
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

        $sentDate = now();
        $status = false;

        try {
            $ccEmail = $request->input('copy_email');
            $documentNameGerman = $documentType === 'invoice' ? 'Rechnung' : 'Angebot';

            Mail::send([], [], function ($message) use (
                $randomFileName, $documentNumber, $email, $subject, $messageBody, 
                $filePath, $senderEmail, $senderName, $ccEmail, $documentNameGerman
            ) {
                $message->from($senderEmail, $senderName)
                        ->to($email)
                        ->subject($subject)
                        ->html($messageBody)
                        ->attach($filePath, [
                            'as' => $documentNameGerman . '_' . $documentNumber . '.pdf',
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
            'objectnumber' => $documentData->number,
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

        $customerdata = $this->generateCustomerData($customer);
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
            <table cellpadding="0.5" width="100%" style="border: 0.5px solid white;">
                
                <tr>
                    <td style="text-align: left;">Zahlungskonditionen</td>
                    <td style="text-align: right;">' . ($condition->conditionname ?? '') . '</td>
                </tr>
                <tr>
                    <td style="text-align: left;">Ihre Kundennummer</td>
                    <td style="text-align: right;">' . ($customer->customer_number ?? '') . '</td>
                </tr>
                <tr>
                    <td style="text-align: left;">Ihre USt-Id.</td>
                    <td style="text-align: right;">' . ($customer->vat_number ?? '') . '</td>
                </tr>
            </table>';

        $formattedDate = $this->formatDate($offercontent->date);

        $offerNumber = '
            <table cellpadding="0.5" cellspacing="0" width = "100%">
                <tr>
                    <td style="text-align: left;">Angebots-Nr.</td>
                    <td style="text-align: right;">' . $offercontent->number . '</td>
                </tr>
            </table>';

        $offerinfo = '
            <table cellpadding="0.5" cellspacing="0" width = "100%">
                <tr>
                    <td style="text-align: left;">Angebotsdatum</td>
                    <td style="text-align: right;">'.$formattedDate.'</td>
                </tr>
                <tr>
                    <td style="text-align: left;">Gültigkeitsdauer</td>
                    <td style="text-align: right;">3 Wochen</td>
                </tr>
            </table>';

        $footer = $this->generateFooter($client);

        $positiontableheader = $this->generatePositionTableHeader($client, $color, $objectType);

        $positiontablebody = $this->generatePositionTableBody($positions);

        $positionsum = $this->generatePositionSum($totalSum, $offercontent->taxrate, $client, $objectType);

        // PDF erstellen
        $pdf = new MyPDF();

        $pdf->AddPage();
        
        // Definiere Seitennummern nach dem Hinzufügen der ersten Seite
        $pageNumber = $pdf->PageNo();
        $totalPages = $pdf->getNumPages();

        

        $pdf->SetFont('arial', '', 10);
        $this->processLogo($pdf, $localImagePath);
        $pdf->SetCreator('Venditio');
        $pdf->SetAuthor('Venditio');
        $pdf->SetTitle('Angebot' . ' ' . $offercontent->number);
        $pdf->SetMargins(10, 0, 10);

        $pdf->SetFont($fontbold, '', 12);
        $pdf->SetXY(123, 44);
        $pdf->writeHTML($offerNumber, true, true, false, true, 'R');
        
        $pdf->SetXY(123, 51);
        $pdf->SetFont($font, '', 10);
        $pdf->writeHTML($offerinfo, true, true, false, true, 'R');

        //Eigene Daten über den Kunden
        $pdf->SetXY(10, 45);
        $pdf->SetFont('arial', '', 7);
        $pdf->writeHTML($clientdata, true, true, false, true, 'R');
        
        $pdf->SetFont($font, '', 10);
        $pdf->SetXY(10, 55);
        $pdf->writeHTML($customerdata, true, true, false, true, 'R');
        
        $pdf->SetXY(123, 61);
        $pdf->SetFont($font, '', 10);
        $pdf->writeHTML($operation, true, true, false, true, 'R');
        
        

        $pdf->SetXY(10, 90);
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

        // Footer als echten PDF-Footer deklarieren
        $pdf->setCustomFooterHTML($footer);

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
        
        $customerdata = $this->generateCustomerData($customer);
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
            <table cellpadding="0.5" width="100%" style="border: 0.5px solid white;">
                
                <tr>
                    <td style="text-align: left;">Zahlungskonditionen</td>
                    <td style="text-align: right;">' . ($condition->conditionname ?? '') . '</td>
                </tr>
                <tr>
                    <td style="text-align: left;">Ihre Kundennummer</td>
                    <td style="text-align: right;">' . ($customer->customer_number ?? '') . '</td>
                </tr>
                <tr>
                    <td style="text-align: left;">Ihre USt-ID</td>
                    <td style="text-align: right;">' . ($customer->vat_number ?? '') . '</td>
                </tr>
            </table>';



        $formattedDate = $this->formatDate($invoicecontent->date ?? null);



        

        $positiontableheader = $this->generatePositionTableHeader($client, $color, $objectType);

        $positiontablebody = $this->generatePositionTableBody($positions);

        $positionsum = $this->generatePositionSum($totalSum, $invoicecontent->taxrate, $client, $objectType, $invoicecontent->reverse_charge ?? false);


            $footer = $this->generateFooter($client);
            
            
            //$client->companyname.", ".$client->address.", ".$client->postalcode." ".$client->location.", Tel.: ".$client->phone.", E-Mail: ".$client->email.", \n".$client->regional_court.", FN-Nr.: ".$client->company_registration_number.", USt.-ID: ".$client->vat_number.", Steuer-Nr.: ".$client->tax_number.", \nGeschäftsführung: ".$client->management.", Bank: ".$client->bank.", IBAN: ".$client->accountnumber.", BIC: ".$client->bic;


            if ($invoicecontent->depositamount > 0) {
                $totalWithTax = ($invoicecontent->reverse_charge ?? false) ? $totalSum : $totalSum*($invoicecontent->taxrate/100+1);
                $positionsum .= '
                    <table cellpadding="2" cellspacing="0" width = "100%">
                        <tr>
                            <td style="text-align: left; width: 70%;"></td>
                            <td style="text-align: left; width: 15%; border-bottom: 0.5px solid black; font-size: 10px;">Anzahlung</td>
                            <td style="text-align: right; width: 15%; border-bottom: 0.5px solid black; font-size: 10px;">-'.number_format($invoicecontent->depositamount, 2, ',', '').' EUR</td>
                        </tr>
                        <tr>
                            <td style="text-align: left; width: 70%;"></td>
                            <td style="text-align: left; width: 15%; border-bottom: 2px solid '.$client->color.'; color: '.$client->color.'; font-family: segoebd; font-size: 11px;">Zu zahlen:</td>
                            <td style="text-align: right; width: 15%; border-bottom: 2px solid '.$client->color.'; color: '.$client->color.';   font-family: segoebd; font-size: 11px;">'.number_format($totalWithTax-$invoicecontent->depositamount, 2, ',', '').' EUR</td>
                        </tr>
                    </table>';
            }

        //$html="test";
        // PDF erstellen
        //define('K_PATH_FONTS', resource_path('fonts/') . '/');
    
        $pdf = new MyPDF();


        $pdf->AddPage();
        $pageNumber = $pdf->PageNo();
        $totalPages = $pdf->getNumPages();
        $pdf->SetFont('segoe', '', 9);
        
        $this->processLogo($pdf, $localImagePath);

        $invoiceinfo = '
            <table cellpadding="0.5" cellspacing="0" width = "100%">
                <tr>
                    <td style="text-align: left; width: 42%;">Rechnungsdatum</td>
                    <td style="text-align: right; width: 58%;">'.$formattedDate.'</td>
                </tr>
                <tr>
                    <td style="text-align: left; width: 42%;">Leistungszeitraum</td>
                    <td style="text-align: right; width: 58%;">' . ($formattedPeriodFrom ?? '') . ' - ' . ($formattedPeriodTo ?? '') . '</td>
                </tr>
                
            </table>';

        $pageinfo = '
            <table cellpadding="0.5" cellspacing="0" width = "100%">
                <tr>
                    <td style="text-align: right; width: 100%;">Seite '.$pageNumber.' von '.$totalPages.'</td>
                </tr>
            </table>';

        $invoiceNumber = '
            <table cellpadding="0.5" cellspacing="0" width = "100%">
                <tr>
                    <td style="text-align: left; width: 40%;">Rechnungs-Nr.</td>
                    <td style="text-align: right; width: 60%;">' . $invoicecontent->number . '</td>
                </tr>
            </table>';

        $invoicetext = '
            <table cellpadding="2" cellspacing="0" width = "100%">
                <tr>
                    <td style="text-align: left;">Vielen Dank für Ihren Auftrag und das damit verbundene Vertrauen! Hiermit stellen wir Ihnen die folgenden Leistungen in Rechnung:</td>
                </tr>
            </table>';

        $biginvoicenumber = '
            <table cellpadding="2" cellspacing="0" width = "100%">
                <tr>
                    <td style="text-align: left; color: '.$client->color.';">Rechnung Nr. ' . $invoicecontent->number . '</td>
                </tr>
            </table>';

        $pdf->SetCreator('Venditio');
        $pdf->SetAuthor('{{$client->name}}');
        
        $pdf->SetTitle('Rechnung' . ' ' . $invoicecontent->number);
        $pdf->SetMargins(10, 10, 10, 10);
        
        //Eigene Daten über den Kunden
        $pdf->SetXY(10, 45);
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
        $pdf->SetXY(123, 44);
        $pdf->writeHTML($invoiceNumber, true, true, false, true, 'R');
        
        //Rechnungsdatum und Leistungszeitraum
        $pdf->SetXY(123, 51);
        $pdf->SetFont($font, '', 10);
        $pdf->writeHTML($invoiceinfo, true, true, false, true, 'R');
        
        //Zahlungskonditionen, Kundennummer, USt-ID
        $pdf->SetXY(123, 62);
        $pdf->SetFont($font, '', 10);
        $pdf->writeHTML($operation, true, true, false, true, 'R');
        
        //Seite
        //$pdf->SetXY(172, 267);
        //$pdf->SetFont($font, '', 10);
        //$pdf->writeHTML($pageinfo, true, true, false, true, 'R');

        //Rechnungsnummer groß links
        $pdf->SetXY(10, 90);
        $pdf->SetFont($font, 'B', 20);
        $pdf->writeHTML($biginvoicenumber, true, true, false, true, 'R');

        //$pdf->Cell(100, 10, 'Rechnung Nr. ' . $invoicecontent->number, 0, 1, 'L');
        

        $pdf->SetXY(10, 105);
        $pdf->SetFont($font, '', 10);
        $pdf->writeHTML($invoicetext, true, true, false, true, 'R');
        
        //$pdf->multiCell(190, 10, $invoicetext, 0, 'L', 0, 1);
        
        $pdf->SetXY(10, 115);
        $pdf->SetFont($fontbold, '', 10);
        $pdf->writeHTML($positiontableheader, true, true, false, true, 'R');
        
        $pdf->SetXY(10, 120);
        $pdf->SetFont($font, '', 10);
        $pdf->writeHTML($positiontablebody, true, true, false, true, 'R');

        $pdf->writeHTML($positionsum, true, true, false, true, 'R');
        
        // Kleinunternehmer-Hinweis oder Reverse Charge Hinweis
        if ($client->smallbusiness) {
            $pdf->writeHTML('<br><br><br><br>
            <table cellpadding="2" cellspacing="0" width = "100%">
                <tr>
                    <td style="text-align: left; width: 7%;"></td>
                    <td style="text-align: left; width: 93%;">Kleinunternehmer gem. § 6 Abs. 1 Z 27 UStG</td>
                </tr>
            </table>', true, true, true, true, 'L');
        } elseif ($invoicecontent->reverse_charge ?? false) {
            $pdf->writeHTML('<br><br><br><br>
            <table cellpadding="2" cellspacing="0" width = "100%">
                <tr>
                    <td style="text-align: left; width: 7%;"></td>
                    <td style="text-align: left; width: 93%;">Umsatzsteuer wird gemäß § 13b UStG vom Leistungsempfänger geschuldet</td>
                </tr>
            </table>', true, true, true, true, 'L');
        }

        // Footer als echten PDF-Footer deklarieren
        
        $pdf->setCustomFooterHTML($footer);

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
    return $this->sendDocumentByEmail($request, 'invoice');
}

public function sendOfferByEmail(Request $request)
{
    return $this->sendDocumentByEmail($request, 'offer');
}




}
