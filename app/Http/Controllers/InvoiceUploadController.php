<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceUpload;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Clients;

class InvoiceUploadController extends Controller
{
    // Zeigt das Upload-Formular an
    public function create()
    {
        return view('invoiceupload.create');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $clientId = $user->client_id;

        // Prüfen ob Rechnung zum Client gehört
        $invoice = InvoiceUpload::where('client_id', $clientId)
            ->where('id', $id)
            ->firstOrFail();

        // Hier kannst du ggf. weitere Daten laden,
        // etwa Dropdown-Werte oder ähnliches.

        return view('invoiceupload.edit', compact('invoice'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $clientId = $user->client_id;

        // Prüfen ob Rechnung zum Client gehört
        $invoice = InvoiceUpload::where('client_id', $clientId)
            ->where('id', $id)
            ->first();

        if (!$invoice) {
            abort(403, 'Sie haben keine Berechtigung, diese Rechnung zu bearbeiten.');
        }

        // Hole Client-Settings für Upload-Größe
        $client = Clients::active()->where('id', $clientId)->first();
        $parentId = $client->parent_client_id ?? $client->id;
        $clientSettings = \App\Models\ClientSettings::where('client_id', $parentId)->first();
        $max_upload_size = $clientSettings ? $clientSettings->max_upload_size : 2048;

        // Felder validieren (inkl. optional PDF)
        $validatedData = $request->validate([
            'invoice_pdf'    => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:10240', // max. 10 MB
                function ($attribute, $value, $fail) use ($max_upload_size) {
                    if ($value && $value->getSize() > $max_upload_size * 1024 * 1024) {
                        $fail('Die PDF-Datei darf nicht größer als ' . $max_upload_size . ' MB sein.');
                    }
                },
            ],
            'invoice_date'   => 'required|date',
            'invoice_vendor' => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'invoice_number' => 'nullable|string|max:255',
        ]);

        // Wenn eine neue PDF-Datei hochgeladen wurde
        if ($request->hasFile('invoice_pdf')) {
            // Alte Datei löschen, falls vorhanden
            if ($invoice->filepath && Storage::exists($invoice->filepath)) {
                Storage::delete($invoice->filepath);
            }

            // Neue Datei speichern
            $path = $request->file('invoice_pdf')->store('invoices');
            $invoice->filepath = $path;
        }

        // Andere Felder aktualisieren
        $invoice->invoice_date   = $validatedData['invoice_date'];
        $invoice->invoice_vendor = $validatedData['invoice_vendor'] ?? null;
        $invoice->description    = $validatedData['description'] ?? null;
        $invoice->invoice_number = $validatedData['invoice_number'] ?? null;

        $invoice->save();

        // Weiterleitung mit Erfolgsmeldung
        $message = $request->hasFile('invoice_pdf') 
            ? 'Rechnungsupload und Datei erfolgreich aktualisiert!' 
            : 'Rechnungsupload erfolgreich aktualisiert!';
            
        return redirect()->route('invoiceupload.index')->with('success', $message);
    }

    // Verarbeitet den Upload
    public function store(Request $request)
    {
        $user = Auth::user();
        $clientId = $user->client_id;
        
        // Hole aktiven Client und seine Settings
        $client = Clients::active()->where('id', $clientId)->first();
        $parentId = $client->parent_client_id ?? $client->id;
        $clientSettings = \App\Models\ClientSettings::where('client_id', $parentId)->first();
        $max_upload_size = $clientSettings ? $clientSettings->max_upload_size : 2048;
        //dd($max_upload_size);
        try {
            // Validierung der eingehenden Daten
            $request->validate([
                'invoice_pdf'    => [
                    'required',
                    'file',
                    'mimes:pdf',
                    'max:10240', // max. 10 MB
                    function ($attribute, $value, $fail) use ($max_upload_size) {
                        if ($value->getSize() > $max_upload_size * 1024 * 1024) {
                            $fail('Die PDF-Datei darf nicht größer als ' . $max_upload_size . ' MB sein.');
                        }
                    },
                ],
                'invoice_date'   => 'required|date',
                'invoice_vendor' => 'required|string',
                'description'    => 'nullable|string',
                'invoice_number' => 'nullable|string',
            ]);

            // Datei speichern
            $path = $request->file('invoice_pdf')->store('invoices');

            //dd($clientId);
            // Daten in der Datenbank speichern
            InvoiceUpload::create([
                'filepath'       => $path,
                'invoice_date'   => $request->input('invoice_date'),
                'description'    => $request->input('description'),
                'invoice_number' => $request->input('invoice_number'),
                'invoice_vendor' => $request->input('invoice_vendor'),
                'client_id'      => $clientId,
            ]);

            return redirect()->route('invoiceupload.index')
                             ->with('success', 'Rechnung erfolgreich hochgeladen!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Beim Hochladen ist ein Fehler aufgetreten: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function index(Request $request)
    {
        // Aktuell eingeloggten Benutzer holen
        $user = Auth::user();
        $clientId = $user->client_id;

        // Suchparameter aus dem Request
        $search = $request->input('search');

        // Grundabfrage
        $query = InvoiceUpload::where('client_id', $clientId)
                              ->orderBy('invoice_date', 'desc');

        // Falls ein Suchtext vorhanden ist, erweitern wir die Abfrage
        // Beispiel: Suche in 'invoice_number' und 'description'
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('invoice_vendor', 'LIKE', "%{$search}%");
            });
        }

        // Paginierte Ergebnisse laden
        $invoiceuploads = $query->paginate(10);

        // Distinkte Monate holen (Year/Month) für zusätzliche Filter (z. B. Dropdown)
        $months = InvoiceUpload::selectRaw('YEAR(invoice_date) as year, MONTH(invoice_date) as month')
            ->where('client_id', $clientId)       // wichtig, damit nur eigene Invoices berücksichtigt werden
            ->distinct()
            ->orderBy('invoice_date', 'desc')
            ->get()
            ->map(function ($invoice) {
                return Carbon::create($invoice->year, $invoice->month, 1)->format('F Y');
            });

        // Ausgabe an View
        return view('invoiceupload.index', compact('invoiceuploads', 'months'));
    }

    public function show($id){
        $user = Auth::user();
        $clientId = $user->client_id;

        // Prüfen ob Rechnung zum Client gehört
        $invoice = InvoiceUpload::where('client_id', $clientId)
            ->where('id', $id)
            ->first();

        if (!$invoice) {
            abort(403, 'Sie haben keine Berechtigung, diese Rechnung anzuzeigen.');
        }

        return view('invoiceupload.show', compact('invoice'));
    }

    public function filterByMonth($month)
    {
        $user = Auth::user();
        $clientId = $user->client_id;

        // Wandle den Monat in ein Carbon-Objekt um
        $month = \Carbon\Carbon::parse($month);

        // Filtere Rechnungen nach Monat und Jahr - NUR eigene Client-Daten
        $invoiceuploads = InvoiceUpload::where('client_id', $clientId)
                                        ->whereMonth('invoice_date', $month->month)
                                        ->whereYear('invoice_date', $month->year)
                                        ->paginate(15);

        // Gib die gefilterten Rechnungen und die Monatsliste an die View zurück
        $months = InvoiceUpload::selectRaw('YEAR(invoice_date) as year, MONTH(invoice_date) as month')
                            ->where('client_id', $clientId)       // wichtig, damit nur eigene Invoices berücksichtigt werden
                            ->distinct()
                            ->orderBy('invoice_date', 'desc')
                            ->get()
                            ->map(function ($invoice) {
                                return \Carbon\Carbon::create($invoice->year, $invoice->month, 1)->format('F Y');
                            });

        return view('invoiceupload.index', compact('invoiceuploads', 'months'));
    }

    public function show_invoice($id)
    {
        
        $user = Auth::user();
        $clientId = $user->client_id;
        //dd($clientId);

        // Prüfen ob Rechnung zum Client gehört
        $invoice = InvoiceUpload::where('client_id', $clientId)
            ->where('id', $id)
            ->first();

        if (!$invoice) {
            abort(403, 'Sie haben keine Berechtigung, diese Rechnung anzuzeigen.');
        }

               //dd($invoice);

        $path = storage_path('app/' . $invoice->filepath);

        if (!file_exists($path)) {
            abort(404, 'Die angeforderte Datei wurde nicht gefunden oder ist nicht verfügbar.');
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function downloadZipForMonth($month)
    {
        $user = Auth::user();
        $clientId = $user->client_id;

        // 1) Monat parsen
        $parsedMonth = \Carbon\Carbon::parse($month);

        // 2) Zip-Dateiname, z. B. "invoices_2025-01.zip"
        $zipFileName = 'invoices_'.$parsedMonth->format('Y-m').'.zip';

        // 3) Kompletter Pfad zum ZIP
        // storage/app/tmp/invoices_2025-01.zip
        $zipPath = str_replace('/', DIRECTORY_SEPARATOR, storage_path('app/tmp/' . $zipFileName));


        // 4) Ggf. Verzeichnis anlegen
        if (!is_dir(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0777, true);
        }

        // 5) ZipArchive öffnen
        $zip = new ZipArchive();
        $res = $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        if ($res !== true) {
            return back()->withErrors(['msg' => 'Fehler beim Erstellen der ZIP: ' . $res]);
        }

        // 6) Beispieldateien hinzufügen - NUR eigene Client-Daten
        $invoices = InvoiceUpload::where('client_id', $clientId)
            ->whereYear('invoice_date', $parsedMonth->year)
            ->whereMonth('invoice_date', $parsedMonth->month)
            ->get();

        //dd($invoices);

        $i = 1;
        foreach ($invoices as $invoice) {
            $filePath = storage_path('app/' . $invoice->filepath);

            if (file_exists($filePath)) {
                // 1) invoice_date formatieren (z.B. YYYY-MM-DD)
                $formattedDate = \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d');

                // 2) Dateiendung aus Originaldatei
                $extension = pathinfo($filePath, PATHINFO_EXTENSION);

                // 3) Neuen Dateinamen bauen, z. B. "2025-01-15_1_ABC123.pdf"
                $newFileName = $formattedDate . '_' . $i . '_' . ($invoice->invoice_vendor ?? 'NR') . '.' . $extension;

                // Datei mit neuem Namen ins ZIP packen
                $zip->addFile($filePath, $newFileName);

                $i++;
            }
        }
        //dd($zip);
        $closeResult = $zip->close();
        //dd($closeResult);
        // 7) ZIP als Download zurückgeben & danach löschen
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }


    public function testCreateZip()
    {
        // 1) Test-Pfad definieren
        $targetDir = storage_path('app/tmp');
        if (! is_dir($targetDir)) {
            // mkdir gibt true/false zurück
            $mkdirResult = mkdir($targetDir, 0777, true);
            if ($mkdirResult === false) {
                dd('Konnte tmp-Verzeichnis nicht anlegen:', $targetDir);
            }
        }

        // 2) Beispiel-Datei definieren (leg sicherheitshalber "test.pdf" in storage/app ab)
        $testFile = storage_path('app/test.pdf');
        if (! file_exists($testFile)) {
            dd('Die Test-Datei existiert NICHT:', $testFile);
        }

        // 3) Pfad für die zu erstellende ZIP
        $zipPath = $targetDir . '/test.zip';

        // 4) ZipArchive öffnen/erstellen
        $zip = new \ZipArchive;
        $res = $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        if ($res !== true) {
            dd('Fehler beim Öffnen/Erstellen der ZIP:', $res);
        }

        // 5) Nur EINE Datei hinzufügen
        $zip->addFile($testFile, 'test.pdf');

        // 6) ZIP schließen & Result debuggen
        $closeResult = $zip->close();

        dd([
            'closeResult'   => $closeResult,
            'zipStatus'     => $zip->status,
            'zipStatusSys'  => $zip->statusSys,
            'file_exists'   => file_exists($zipPath),
            'zipPath'       => $zipPath,
        ]);
    }


}
