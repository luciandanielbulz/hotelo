<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Prüfe Berechtigung
        if (!Auth::user()->hasPermission('manage_currencies')) {
            abort(403, 'Zugriff verweigert. Sie haben keine Berechtigung, Währungen zu verwalten.');
        }

        $user = Auth::user();
        $clientId = $user->client_id;
        
        $currencies = Currency::where('client_id', $clientId)
            ->orderBy('is_default', 'desc')
            ->orderBy('code')
            ->get();
            
        return view('currencies.index', compact('currencies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Prüfe Berechtigung
        if (!Auth::user()->hasPermission('manage_currencies')) {
            abort(403, 'Zugriff verweigert. Sie haben keine Berechtigung, Währungen zu erstellen.');
        }

        return view('currencies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Prüfe Berechtigung
        if (!Auth::user()->hasPermission('manage_currencies')) {
            abort(403, 'Zugriff verweigert. Sie haben keine Berechtigung, Währungen zu erstellen.');
        }

        $validated = $request->validate([
            'code' => 'required|string|size:3|alpha',
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10',
            'exchange_rate' => 'required|numeric|min:0.0001',
            'is_default' => 'boolean',
        ]);

        $user = Auth::user();
        $validated['client_id'] = $user->client_id;
        $validated['code'] = strtoupper($validated['code']);

        // Prüfe ob Währungscode bereits für diesen Client existiert
        $exists = Currency::where('client_id', $user->client_id)
            ->where('code', $validated['code'])
            ->exists();
            
        if ($exists) {
            return redirect()->back()
                ->withErrors(['code' => 'Eine Währung mit diesem Code existiert bereits.'])
                ->withInput();
        }

        $currency = Currency::create($validated);

        // Wenn als Standard markiert, setze als Standard
        if ($request->has('is_default') && $request->is_default) {
            $currency->setAsDefault();
        }

        return redirect()->route('currencies.index')
            ->with('success', 'Währung erfolgreich erstellt.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Currency $currency)
    {
        // Prüfe Berechtigung und Zugehörigkeit
        if (!Auth::user()->hasPermission('manage_currencies')) {
            abort(403, 'Zugriff verweigert.');
        }

        if ($currency->client_id !== Auth::user()->client_id) {
            abort(403, 'Zugriff verweigert.');
        }

        return view('currencies.show', compact('currency'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Currency $currency)
    {
        // Prüfe Berechtigung und Zugehörigkeit
        if (!Auth::user()->hasPermission('manage_currencies')) {
            abort(403, 'Zugriff verweigert.');
        }

        if ($currency->client_id !== Auth::user()->client_id) {
            abort(403, 'Zugriff verweigert.');
        }

        return view('currencies.edit', compact('currency'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Currency $currency)
    {
        // Prüfe Berechtigung und Zugehörigkeit
        if (!Auth::user()->hasPermission('manage_currencies')) {
            abort(403, 'Zugriff verweigert.');
        }

        if ($currency->client_id !== Auth::user()->client_id) {
            abort(403, 'Zugriff verweigert.');
        }

        $validated = $request->validate([
            'code' => 'required|string|size:3|alpha',
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10',
            'exchange_rate' => 'required|numeric|min:0.0001',
            'is_default' => 'boolean',
        ]);

        $validated['code'] = strtoupper($validated['code']);

        // Prüfe ob Währungscode bereits für diesen Client existiert (außer für diese Währung)
        $exists = Currency::where('client_id', $currency->client_id)
            ->where('code', $validated['code'])
            ->where('id', '!=', $currency->id)
            ->exists();
            
        if ($exists) {
            return redirect()->back()
                ->withErrors(['code' => 'Eine Währung mit diesem Code existiert bereits.'])
                ->withInput();
        }

        $currency->update($validated);

        // Wenn als Standard markiert, setze als Standard
        if ($request->has('is_default') && $request->is_default) {
            $currency->setAsDefault();
        }

        return redirect()->route('currencies.index')
            ->with('success', 'Währung erfolgreich aktualisiert.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Currency $currency)
    {
        // Prüfe Berechtigung und Zugehörigkeit
        if (!Auth::user()->hasPermission('manage_currencies')) {
            abort(403, 'Zugriff verweigert.');
        }

        if ($currency->client_id !== Auth::user()->client_id) {
            abort(403, 'Zugriff verweigert.');
        }

        // Prüfe ob Währung in Verwendung ist
        $inUse = \App\Models\InvoiceUpload::where('currency_id', $currency->id)->exists();
        
        if ($inUse) {
            return redirect()->route('currencies.index')
                ->with('error', 'Diese Währung kann nicht gelöscht werden, da sie noch in Rechnungen verwendet wird.');
        }

        $currency->delete();

        return redirect()->route('currencies.index')
            ->with('success', 'Währung erfolgreich gelöscht.');
    }
}
