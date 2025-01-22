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



class PdfCreateController extends Controller
{

        public function createOfferPdf(Request $request)
    {
        //dd($request);
        $user = Auth::user();
        $clientId = $user->client_id;


        $objectId = $request->input('offer_id');
        $objectType = $request->input('objecttype'); // "offer" oder "invoice"
        $preview = $request->input('prev', 0); // 0: Download, 1: Vorschau, 2: Speichern


        $offercontent = Offers::where('offers.id', $objectId)
            ->join('taxrates','offers.tax_id','=','taxrates.id')
            ->first(['offers.*','taxrates.*']);

        dd($offercontent);

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


        $client = Clients::where('id', $clientId)->firstOrFail();

        $customer = Customer::join('offers','customers.id','=','offers.customer_id')
            ->where('offers.id','=',$objectId)
            ->first('customers.*');

        $logopath = Logos::where('client_id','=',$clientId)->firstOrFail();
        //dd($logopath);

        //$html = view('pdf.template', compact('offercontent','positions','client', 'condition', 'customer'));
        $imagePath = $imagePath = public_path('storage/' . $logopath->localfilename);
        $imageHeight = $client->logoheight;
        $imageWidth = $client->logowidth;
        //dd($imageHeight);

        $customerdata = '
            <table cellpadding="1" cellspacing="0" style="width:100%;">
                <tr>
                    <td style="text-align: left;">'.$customer->companyname.'</td>
                </tr>
                <tr>
                    <td style="text-align: left;">'.$customer->customername.'</td>
                </tr>
                <tr>
                    <td style="text-align: left;">'.$customer->address.'</td>
                </tr>
                <tr>
                    <td style="text-align: left;">'.$customer->postalcode.' '.$customer->location.'</td>
                </tr>
                <tr>
                    <td style="text-align: left;">'.$customer->country.'</td>
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
            <table cellpadding="2" width="222" style="border: 0.5px solid black;">
                <tr>
                    <td style="text-align: right;">Gültigkeitsdauer:</td>
                    <td style="text-align: right;">3 Wochen</td>
                </tr>
                <tr>
                    <td style="text-align: right;">Ihr Ansprechpartner:</td>
                    <td style="text-align: right;">'.$client->clientname.'</td>
                </tr>
                <tr>
                    <td style="text-align: right;">Zahlungskonditionen:</td>
                    <td style="text-align: right;">'.$condition->conditionname.'</td>
                </tr>
                <tr>
                    <td style="text-align: right;">Ihre UID-Nummer:</td>
                    <td style="text-align: right;">'.$customer->vat_number.'</td>
                </tr>
            </table>';

        $formattedDate = \Carbon\Carbon::parse($offercontent->date)->format('d.m.Y');



        $positiontableheader = '
            <table cellpadding="2" cellspacing="0" width = "533" style=" background-color: rgb(243, 243, 243);">
                <tr>
                    <td style="text-align: left; width: 9%;">Menge</td>
                    <td style="text-align: left; width: 9%;">Einheit</td>
                    <td style="text-align: left; width: 52%;">Bezeichnung</td>
                    <td style="text-align: center; width: 15%;">Einzelpreis</td>
                    <td style="text-align: center; width: 15%;">Betrag</td>
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
                        <td style="text-align: center; width: 15%;">'.number_format($position->price, 2, ',', '') .' €</td>
                        <td style="text-align: center; width: 15%;">'.number_format(($position->price * $position->amount), 2, ',', '') .' €</td>
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
            <table cellpadding="2" cellspacing="0" width = "533" style="border-top: 0.5px solid black;">
                <tr>
                    <td style="text-align: left; width: 70%;"></td>
                    <td style="text-align: left; width: 15%;">Netto:</td>
                    <td style="text-align: center; width: 15%;">'.number_format($totalSum, 2, ',', '').' €</td>
                </tr>
                <tr>
                    <td style="text-align: left; width: 70%;"></td>
                    <td style="text-align: left; width: 15%; border-bottom: 0.5px solid black;">'.$offercontent->taxrate .'% Ust:</td>
                    <td style="text-align: center; width: 15%; border-bottom: 0.5px solid black;">'.number_format($totalSum*$offercontent->taxrate/100, 2, ',', '').' €</td>
                </tr>
                <tr>
                    <td style="text-align: left; width: 70%;"></td>
                    <td style="text-align: left; width: 15%; border-bottom: 2px solid black;">Brutto:</td>
                    <td style="text-align: center; width: 15%; border-bottom: 2px solid black;">'.number_format($totalSum*($offercontent->taxrate/100+1), 2, ',', '').' €</td>
                </tr>
            </table>';


            $footer = $client->webpage.", ".$client->address.", ".$client->postalcode." ".$client->location.", Tel ".$client->phone.",\n".$client->email.", ".$client->bank.", ".$client->accountnumber.", ".$client->bic;


        //$html="test";
        // PDF erstellen
        $pdf = new MyPDF();

        $pdf->setCustomFooterText($footer);

        $pdf->AddPage();
        $pageNumber = $pdf->PageNo();
        $totalPages = $pdf->getNumPages();
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Image($imagePath, 18, 12, $imageWidth, $imageHeight);
        $pdf->SetCreator('Venditio');
        $pdf->SetAuthor('Lucian Bulz');
        $pdf->SetTitle('Angebot' . ' ' . $offercontent->number);
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetXY(15, 55);
        $pdf->writeHTML($customerdata, true, true, false, true, 'R');
        $pdf->SetXY(120, 12);
        $pdf->writeHTML($clienttable, true, true, false, true, 'R');
        $pdf->SetXY(120, 54);
        $pdf->writeHTML($operation, true, true, false, true, 'R');
        $pdf->SetXY(15, 90);
        $pdf->SetFont('helvetica', '', 20);
        $pdf->Cell(100, 10, 'Angebot ' . $offercontent->number, 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetXY(134, 78);
        $pageinfo = '
            <table cellpadding="2" cellspacing="0" width = "182" style="border: 0.5px solid black;">
                <tr>
                    <td style="text-align: right;">Angebotsdatum:</td>
                    <td style="text-align: right;">'.$formattedDate.'</td>
                </tr>
                <tr>
                    <td style="text-align: right;">Seite:</td>
                    <td style="text-align: right;">'.$pageNumber.' von '.$totalPages.'</td>
                </tr>
            </table>';
        $pdf->writeHTML($pageinfo, true, true, false, true, 'R');
        $pdf->SetXY(10, 105);
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



        $user = Auth::user();
        $clientId = $user->client_id;

        $objectId = $request->input('invoice_id');
        $objectType = $request->input('objecttype'); // "offer" oder "invoice"
        $preview = $request->input('prev', 0); // 0: Download, 1: Vorschau, 2: Speichern
        //dd($objectId);
        //dd($request->all());
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


        $client = Clients::where('id', $clientId)->firstOrFail();

        $customer = Customer::join('invoices','customers.id','=','invoices.customer_id')
            ->where('invoices.id','=',$objectId)
            ->first('customers.*');

        //$html = view('pdf.template', compact('offercontent','positions','client', 'condition', 'customer'));
        $logopath = Logos::where('client_id','=',$clientId)->firstOrFail();
        //dd($logopath);

        //$html = view('pdf.template', compact('offercontent','positions','client', 'condition', 'customer'));
        $imagePath = $imagePath = public_path('storage/' . $logopath->localfilename);
        $imageHeight = $client->logoheight;
        $imageWidth = $client->logowidth;
        //dd($imageHeight);
        $customerdata = '
            <table cellpadding="1" cellspacing="0" style="width:100%;">
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
            <table cellpadding="2" width="233" style="border: 0.5px solid black; width: 100%;">
                <tr>
                    <td style="text-align: right; width: 45%;">Ihr Ansprechpartner:</td>
                    <td style="text-align: right;">' . ($client->clientname ?? '') . '</td>
                </tr>
                <tr>
                    <td style="text-align: right; width: 45%;">Zahlungskonditionen:</td>
                    <td style="text-align: right;">' . ($condition->conditionname ?? '') . '</td>
                </tr>
                <tr>
                    <td style="text-align: right; width: 45%;">Ihre UID-Nummer:</td>
                    <td style="text-align: right;">' . ($customer->vat_number ?? '') . '</td>
                </tr>
                <tr>
                    <td style="text-align: right; width: 45%;">Leistungszeitraum:</td>
                    <td style="text-align: right;">' . ($formattedPeriodFrom ?? '') . ' - ' . ($formattedPeriodTo ?? '') . '</td>
                </tr>
            </table>';



        $formattedDate = $invoicecontent && $invoicecontent->date
            ? \Carbon\Carbon::parse($invoicecontent->date)->format('d.m.Y')
            : '';




        $positiontableheader = '
            <table cellpadding="2" cellspacing="0" width = "533" style=" background-color: rgb(243, 243, 243);">
                <tr>
                    <td style="text-align: left; width: 9%;">Menge</td>
                    <td style="text-align: left; width: 9%;">Einheit</td>
                    <td style="text-align: left; width: 52%;">Bezeichnung</td>
                    <td style="text-align: right; width: 15%;">Einzelpreis</td>
                    <td style="text-align: right; width: 15%;">Betrag</td>
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
                        <td style="text-align: right; width: 15%;">'.number_format($position->price, 2, ',', '') .' €</td>
                        <td style="text-align: right; width: 15%;">'.number_format(($position->price * $position->amount), 2, ',', '') .' €</td>
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
            <table cellpadding="2" cellspacing="0" width = "533" style="border-top: 0.5px solid black;">
                <tr>
                    <td style="text-align: left; width: 70%;"></td>
                    <td style="text-align: left; width: 15%;">Netto:</td>
                    <td style="text-align: right; width: 15%;">'.number_format($totalSum, 2, ',', '').' €</td>
                </tr>
                <tr>
                    <td style="text-align: left; width: 70%;"></td>
                    <td style="text-align: left; width: 15%; border-bottom: 0.5px solid black;">'.$invoicecontent->taxrate .'% Ust:</td>
                    <td style="text-align: right; width: 15%; border-bottom: 0.5px solid black;">'.number_format($totalSum*$invoicecontent->taxrate/100, 2, ',', '').' €</td>
                </tr>
                <tr>
                    <td style="text-align: left; width: 70%;"></td>
                    <td style="text-align: left; width: 15%; border-bottom: 1px solid black;">Brutto:</td>
                    <td style="text-align: right; width: 15%; border-bottom: 1px solid black;">'.number_format($totalSum*($invoicecontent->taxrate/100+1), 2, ',', '').' €</td>
                </tr>
            </table>';


            $footer = $client->webpage.", ".$client->address.", ".$client->postalcode." ".$client->location.", Tel ".$client->phone.",\n".$client->email.", ".$client->bank.", ".$client->accountnumber.", ".$client->bic;


            if ($invoicecontent->depositamount > 0) {
                $positionsum .= '
                    <table cellpadding="2" cellspacing="0" width = "533">
                        <tr>
                            <td style="text-align: left; width: 70%;"></td>
                            <td style="text-align: left; width: 15%; border-bottom: 0.5px solid black;">Anzahlung</td>
                            <td style="text-align: right; width: 15%; border-bottom: 0.5px solid black;">-'.number_format($invoicecontent->depositamount, 2, ',', '').' €</td>
                        </tr>
                        <tr>
                            <td style="text-align: left; width: 70%;"></td>
                            <td style="text-align: left; width: 15%; border-bottom: 2px solid black;">Zu zahlen:</td>
                            <td style="text-align: right; width: 15%; border-bottom: 2px solid black;">'.number_format($totalSum*($invoicecontent->taxrate/100+1)-$invoicecontent->depositamount, 2, ',', '').' €</td>
                        </tr>
                    </table>';
            }

        //$html="test";
        // PDF erstellen
        $pdf = new MyPDF();

        $pdf->setCustomFooterText($footer);

        $pdf->AddPage();
        $pageNumber = $pdf->PageNo();
        $totalPages = $pdf->getNumPages();
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Image($imagePath, 18, 12, $imageWidth, $imageHeight);
        $pdf->SetCreator('Venditio');
        $pdf->SetAuthor('{{$client->name}}');
        $pdf->SetTitle('Rechnung' . ' ' . $invoicecontent->number);
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetXY(15, 55);
        $pdf->writeHTML($customerdata, true, true, false, true, 'R');
        $pdf->SetXY(120, 12);
        $pdf->writeHTML($clienttable, true, true, false, true, 'R');
        $pdf->SetXY(120, 54);
        $pdf->writeHTML($operation, true, true, false, true, 'R');
        $pdf->SetXY(15, 90);
        $pdf->SetFont('helvetica', '', 20);
        $pdf->Cell(100, 10, 'Rechnung ' . $invoicecontent->number, 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetXY(134, 78);
        $pageinfo = '
            <table cellpadding="2" cellspacing="0" width = "182" style="border: 0.5px solid black;">
                <tr>
                    <td style="text-align: right;">Rechnungsdatum:</td>
                    <td style="text-align: right;">'.$formattedDate.'</td>
                </tr>
                <tr>
                    <td style="text-align: right;">Seite:</td>
                    <td style="text-align: right;">'.$pageNumber.' von '.$totalPages.'</td>
                </tr>
            </table>';
        $pdf->writeHTML($pageinfo, true, true, false, true, 'R');
        $pdf->SetXY(11, 105);
        $text = "Vielen Dank für Ihren Auftrag und das damit entgegengebrachte Vertrauen. Gerne stelle ich Ihnen hiermit die folgende Leistung in Rechnung:";
        $pdf->multiCell(190, 10, $text, 0, 'L', 0, 1);
        $pdf->SetXY(10, 115);
        $pdf->writeHTML($positiontableheader, true, true, false, true, 'R');
        $pdf->SetXY(10, 120);
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




}
