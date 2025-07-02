<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class MaintenanceController extends Controller
{
    /**
     * Zeigt die Wartungsmodus-Verwaltung
     */
    public function index()
    {
        // Prüfe ob App im Wartungsmodus ist
        $isInMaintenance = $this->isInMaintenanceMode();
        
        return view('maintenance.index', compact('isInMaintenance'));
    }

    /**
     * App in Wartungsmodus versetzen
     */
    public function enable(Request $request)
    {
        $user = Auth::user();
        
        // Validierung der Eingaben
        $validated = $request->validate([
            'message' => 'nullable|string|max:500',
            'allowed_ips' => 'nullable|string',
            'duration' => 'nullable|integer|min:1|max:1440' // Max 24 Stunden
        ]);

        try {
            // Baue den Artisan-Befehl zusammen
            $command = [];
            
            if (!empty($validated['message'])) {
                $command['--message'] = $validated['message'];
            }
            
            if (!empty($validated['allowed_ips'])) {
                // IPs durch Komma getrennt
                $ips = explode(',', $validated['allowed_ips']);
                $command['--allow'] = array_map('trim', $ips);
            }
            
            if (!empty($validated['duration'])) {
                $command['--retry'] = $validated['duration'] * 60; // Minuten zu Sekunden
            }

            // Führe Wartungsmodus ein
            Artisan::call('down', array_filter($command));
            
            // Log die Aktion
            \Log::info('Wartungsmodus aktiviert', [
                'user' => $user->name,
                'user_id' => $user->id,
                'message' => $validated['message'] ?? 'Standard-Wartungsmeldung',
                'allowed_ips' => $validated['allowed_ips'] ?? 'keine',
                'duration' => $validated['duration'] ?? 'unbegrenzt'
            ]);

            return redirect()->route('maintenance.index')
                           ->with('success', 'Wartungsmodus wurde erfolgreich aktiviert.');

        } catch (\Exception $e) {
            \Log::error('Fehler beim Aktivieren des Wartungsmodus: ' . $e->getMessage());
            
            return redirect()->back()
                           ->withErrors(['error' => 'Fehler beim Aktivieren des Wartungsmodus: ' . $e->getMessage()]);
        }
    }

    /**
     * App aus Wartungsmodus nehmen
     */
    public function disable()
    {
        $user = Auth::user();
        
        try {
            // Wartungsmodus deaktivieren
            Artisan::call('up');
            
            // Log die Aktion
            \Log::info('Wartungsmodus deaktiviert', [
                'user' => $user->name,
                'user_id' => $user->id
            ]);

            return redirect()->route('maintenance.index')
                           ->with('success', 'Wartungsmodus wurde erfolgreich deaktiviert.');

        } catch (\Exception $e) {
            \Log::error('Fehler beim Deaktivieren des Wartungsmodus: ' . $e->getMessage());
            
            return redirect()->back()
                           ->withErrors(['error' => 'Fehler beim Deaktivieren des Wartungsmodus: ' . $e->getMessage()]);
        }
    }

    /**
     * Prüft ob die App im Wartungsmodus ist
     */
    private function isInMaintenanceMode()
    {
        return File::exists(storage_path('framework/down'));
    }

    /**
     * Holt Wartungsmodus-Informationen
     */
    public function getMaintenanceInfo()
    {
        if (!$this->isInMaintenanceMode()) {
            return null;
        }

        $downFile = storage_path('framework/down');
        if (File::exists($downFile)) {
            $content = File::get($downFile);
            return json_decode($content, true);
        }

        return null;
    }
}
