<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankData;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BankdataController extends Controller
{
    // Zeigt das Upload-Formular
    public function showUploadForm()
    {
        //dd('test');
        return view('bankdata.upload');
    }

    // Verarbeitet den CSV-Upload
    public function uploadJSON(Request $request)
{
    $user = Auth::user();
    $client_id = $user->client_id;

    // Validierung
    $request->validate([
        'json_file' => 'required|file|mimes:json|max:2048',
    ]);


    // JSON-Datei lesen
    $file = $request->file('json_file');

    $jsonData = file_get_contents($file->getRealPath());

    $data = json_decode($jsonData, true); // JSON in ein Array umwandeln


    if (json_last_error() !== JSON_ERROR_NONE) {
        return redirect()->back()->with('error', 'Ungültiges JSON-Format.');
    }

    // Verarbeitung der Daten
    foreach ($data as $index => $row) {


        // Prüfen und Zugriff auf PartnerAccount-Felder
        $partnerAccount = $row['partnerAccount'] ?? [];

        // Extrahiere spezifische Felder wie IBAN
        $iban = $partnerAccount['iban'] ?? null;
        $bic = $partnerAccount['bic'] ?? null;

        $amountdata = $row['amount'] ?? [];
        //dd($amountdata);
        $amount = $amountdata['value']/100 ?? null;
        $currency = $amountdata['currency'] ?? null;
        // Datum konvertieren (falls nötig)
        $bookingDate = isset($row['booking']) ? \DateTime::createFromFormat('Y-m-d\TH:i:s.uP', $row['booking']) : null;
        $valuationDate = isset($row['valuation']) ? \DateTime::createFromFormat('Y-m-d\TH:i:s.uP', $row['valuation']) : null;
        //dd($iban);

        $newreferencenumber = $row['referenceNumber'];

        $reference = Bankdata::where('referencenumber','=',$newreferencenumber)
            ->first();
        //dd($client_id);

        if (!$reference) {
            // In die Datenbank einfügen
            $bankwrite = BankData::create([
                'transaction_id' => $row['transactionId'] ?? null,
                'contained_transaction_id' => $row['containedTransactionId'] ?? null,
                'date' => $bookingDate ? $bookingDate->format('Y-m-d') : null,
                'partnername' => $row['partnerName'] ?? null,
                'partneriban' => $iban, // Hier direkt die IBAN speichern
                'partnerbic' => $bic, // Hier direkt die IBAN speichern
                'amount' => $amount, // Hier direkt die IBAN speichern
                'currency' => $currency, // Hier direkt die IBAN speichern
                'referencenumber' => $row['referenceNumber'] ?? null,
                'reference' => $row['reference'] ?? null,
                'client_id' => $client_id,
            ]);
            //dd($bankwrite);
        }
    }


    return redirect()->back()->with('success', 'JSON-Datei erfolgreich hochgeladen und importiert!');
}

}
