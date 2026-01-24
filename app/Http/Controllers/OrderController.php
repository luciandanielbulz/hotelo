<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Clients;
use App\Models\Country;
use App\Models\ClientSettings;
use App\Models\Taxrates;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view_orders');
    }

    /**
     * Zeigt alle Bestellungen an
     */
    public function index(Request $request, Clients $client)
    {
        $query = Order::query()->orderBy('created_at', 'desc');
        
        // Filter nach Status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        // Filter nach Plan
        if ($request->has('plan') && $request->plan !== '') {
            $query->where('plan', $request->plan);
        }
        
        // Suche
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }
        
        $orders = $query->paginate(20);
        
        // Statistiken
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processed' => Order::where('status', 'processed')->count(),
            'completed' => Order::where('status', 'completed')->count(),
        ];
        
        return view('orders.index', compact('orders', 'stats', 'client'));
    }

    /**
     * Zeigt Details einer Bestellung
     */
    public function show(Clients $client, Order $order)
    {
        return view('orders.show', compact('order', 'client'));
    }

    /**
     * Aktualisiert den Status einer Bestellung
     */
    public function updateStatus(Request $request, Clients $client, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processed,completed,cancelled',
        ]);
        
        $order->update(['status' => $validated['status']]);
        
        return redirect()->route('orders.index', $client)
            ->with('success', 'Status erfolgreich aktualisiert.');
    }

    /**
     * Erstellt einen Client aus einer Order
     */
    public function createClientFromOrder(Clients $client, Order $order)
    {
        try {
            DB::beginTransaction();

            // Prüfe ob bereits ein Client mit dieser E-Mail existiert
            $existingClient = Clients::where('email', $order->email)->first();
            if ($existingClient) {
                DB::rollBack();
                return redirect()->route('orders.show', [$client, $order])
                    ->with('error', 'Ein Client mit dieser E-Mail-Adresse existiert bereits.');
            }

            // Hole Country-ID aus ISO-Code
            $country = Country::where('iso_code', $order->country)->first();
            if (!$country) {
                // Fallback: Versuche Österreich (AT) zu finden
                $country = Country::where('iso_code', 'AT')->first();
            }

            // Hole Standard-Taxrate (erste verfügbare)
            $taxrate = Taxrates::first();
            if (!$taxrate) {
                DB::rollBack();
                return redirect()->route('orders.show', [$client, $order])
                    ->with('error', 'Keine Steuersätze gefunden. Bitte erstellen Sie zuerst einen Steuersatz.');
            }

            // Mappe Order-Daten zu Client-Daten
            $clientData = [
                'clientname' => $order->name,
                'companyname' => $order->company ?: $order->name,
                'business' => 'Sonstiges', // Standardwert, kann später geändert werden
                'address' => $order->street,
                'postalcode' => (int)$order->postal_code,
                'location' => $order->city,
                'country_id' => $country ? $country->id : null,
                'email' => $order->email,
                'phone' => $order->phone ?: '',
                'tax_id' => $taxrate->id,
                'vat_number' => $order->uid_number,
                'smallbusiness' => $order->is_kleinunternehmer ?? false,
                'bank' => '', // Nicht in Order verfügbar
                'accountnumber' => '', // Nicht in Order verfügbar
                'bic' => '', // Nicht in Order verfügbar
                'is_active' => true,
                'version' => 1,
                'valid_from' => now(),
                'valid_to' => null,
                'parent_client_id' => null,
            ];

            // Erstelle Client
            $newClient = Clients::create($clientData);

            // Erstelle ClientSettings mit Standardwerten
            ClientSettings::create([
                'client_id' => $newClient->id,
                'lastinvoice' => 0,
                'lastoffer' => 0,
                'invoicemultiplikator' => 1,
                'offermultiplikator' => 1,
                'invoice_number_format' => null,
                'max_upload_size' => 2048,
                'invoice_prefix' => null,
                'offer_prefix' => null,
            ]);

            // Aktualisiere Order-Status auf "processed"
            $order->update(['status' => 'processed']);

            DB::commit();

            Log::info('Client aus Order erstellt', [
                'order_id' => $order->id,
                'client_id' => $newClient->id,
            ]);

            return redirect()->route('clients.edit', $newClient)
                ->with('success', 'Client erfolgreich aus der Bestellung erstellt.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Fehler beim Erstellen des Clients aus Order', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('orders.show', [$client, $order])
                ->with('error', 'Fehler beim Erstellen des Clients: ' . $e->getMessage());
        }
    }
}
