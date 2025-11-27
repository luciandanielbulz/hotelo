<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Clients extends Model
{
    use HasFactory;

    protected $fillable = [
        'clientname',
        'companyname',
        'business',
        'address',
        'postalcode',
        'location',
        'email',
        'phone',
        'tax_id',
        'webpage',
        'bank',
        'accountnumber',
        'vat_number',
        'bic',
        'smallbusiness',
        'logo',
        'signature',
        'document_footer',
        'style',
        'company_registration_number',
        'tax_number',
        'dgnr',
        'management',
        'regional_court',
        'color',
        'valid_from',
        'valid_to',
        'is_active',
        'parent_client_id',
        'version'
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
        'is_active' => 'boolean',
        'smallbusiness' => 'boolean'
    ];

    /**
     * ================================================================
     * VERSIONIERUNGS-METHODEN
     * ================================================================
     */

    /**
     * Scope für nur aktive Clients
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope für Clients die zu einem bestimmten Zeitpunkt gültig waren
     */
    public function scopeValidAt($query, $datetime = null)
    {
        $datetime = $datetime ?? now();
        
        return $query->where('valid_from', '<=', $datetime)
                    ->where(function($q) use ($datetime) {
                        $q->whereNull('valid_to')
                          ->orWhere('valid_to', '>', $datetime);
                    });
    }

    /**
     * ================================================================
     * BEZIEHUNGEN
     * ================================================================
     */

    /**
     * Beziehung zu den Client-Einstellungen (statische Daten)
     */
    public function settings()
    {
        $parentId = $this->parent_client_id ?? $this->id;
        return $this->hasOne(ClientSettings::class, 'client_id', $parentId);
    }

    /**
     * Parent Client Relationship (für Versionierung)
     */
    public function parentClient()
    {
        return $this->belongsTo(Clients::class, 'parent_client_id');
    }

    /**
     * Child Versions Relationship
     */
    public function versions()
    {
        return $this->hasMany(Clients::class, 'parent_client_id')->orderBy('version', 'desc');
    }

    /**
     * Alle Versionen eines Clients (inklusive sich selbst)
     */
    public function allVersions()
    {
        $parentId = $this->parent_client_id ?? $this->id;
        
        return self::where('id', $parentId)
                   ->orWhere('parent_client_id', $parentId)
                   ->orderBy('version', 'desc');
    }

    /**
     * Holt die aktuelle aktive Version eines Clients
     */
    public function getCurrentVersion()
    {
        if ($this->is_active) {
            return $this;
        }

        $parentId = $this->parent_client_id ?? $this->id;
        
        return self::where(function($query) use ($parentId) {
                        $query->where('id', $parentId)
                              ->orWhere('parent_client_id', $parentId);
                    })
                   ->where('is_active', true)
                   ->first();
    }

    /**
     * Erstellt eine neue Version dieses Clients
     */
    public function createNewVersion(array $newData = [])
    {
        DB::beginTransaction();
        
        try {
            // Markiere die aktuelle Version als inaktiv und setze valid_to
            $this->update([
                'is_active' => false,
                'valid_to' => now()
            ]);

            // Bestimme parent_client_id und nächste Versionsnummer
            $parentId = $this->parent_client_id ?? $this->id;
            $nextVersion = self::where('parent_client_id', $parentId)
                              ->orWhere('id', $parentId)
                              ->max('version') + 1;

            // Erstelle neue Version mit allen aktuellen Daten
            // Verwende getAttributes() statt toArray() um sicherzustellen, dass alle Felder enthalten sind
            $newVersionData = $this->getAttributes();
            
            // Entferne ID und Timestamps für neue Version
            unset($newVersionData['id'], $newVersionData['created_at'], $newVersionData['updated_at']);
            
            // Setze Versionierungs-Daten
            $newVersionData['parent_client_id'] = $parentId;
            $newVersionData['version'] = $nextVersion;
            $newVersionData['is_active'] = true;
            $newVersionData['valid_from'] = now();
            $newVersionData['valid_to'] = null;
            
            // Überschreibe mit neuen Daten
            $newVersionData = array_merge($newVersionData, $newData);
            
            // Stelle sicher, dass das Logo-Feld explizit gesetzt ist (auch wenn null)
            if (isset($newData['logo'])) {
                $newVersionData['logo'] = $newData['logo'];
                \Log::info('Logo explizit in newVersionData gesetzt: ' . ($newData['logo'] ?? 'null'));
            } elseif (!isset($newVersionData['logo'])) {
                // Falls Logo nicht in newVersionData ist, übernehme es von der alten Version
                $newVersionData['logo'] = $this->logo;
                \Log::info('Logo von alter Version übernommen: ' . ($this->logo ?? 'null'));
            }
            
            // Logging für Debugging
            \Log::info('Erstelle neue Version mit folgenden Daten:', [
                'logo' => $newVersionData['logo'] ?? 'nicht gesetzt',
                'companyname' => $newVersionData['companyname'] ?? 'nicht gesetzt',
                'version' => $newVersionData['version'] ?? 'nicht gesetzt'
            ]);
            
            // Erstelle neue Version
            $newVersion = self::create($newVersionData);
            
            // Verifiziere dass das Logo korrekt gespeichert wurde
            if ($newVersion->logo) {
                $logoPath = storage_path('app/public/logos/' . $newVersion->logo);
                if (file_exists($logoPath)) {
                    \Log::info('Logo in neuer Version verifiziert: ' . $newVersion->logo . ' (Version ID: ' . $newVersion->id . ', Pfad existiert)');
                } else {
                    \Log::warning('Logo in neuer Version gespeichert, aber Datei existiert nicht: ' . $logoPath);
                    // Prüfe auch mit Storage-Facade
                    if (\Storage::disk('public')->exists('logos/' . $newVersion->logo)) {
                        \Log::info('Logo existiert über Storage-Facade: ' . \Storage::disk('public')->path('logos/' . $newVersion->logo));
                    }
                }
            } else {
                \Log::warning('Neue Version wurde ohne Logo erstellt (Version ID: ' . $newVersion->id . ')');
            }
            
            DB::commit();
            
            return $newVersion;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Holt den Client der zu einem bestimmten Zeitpunkt für eine ID gültig war
     */
    public static function getValidVersionAt($clientId, $datetime = null)
    {
        $datetime = $datetime ?? now();
        
        return self::where(function($query) use ($clientId) {
                        $query->where('id', $clientId)
                              ->orWhere('parent_client_id', $clientId);
                    })
                   ->validAt($datetime)
                   ->first();
    }

    /**
     * Prüft ob diese Version gelöscht werden kann
     */
    public function canBeDeleted()
    {
        $parentId = $this->parent_client_id ?? $this->id;
        
        // Anzahl aller Versionen
        $totalVersions = self::where(function($query) use ($parentId) {
                            $query->where('id', $parentId)
                                  ->orWhere('parent_client_id', $parentId);
                        })->count();

        // Kann nicht gelöscht werden wenn es die einzige Version ist
        if ($totalVersions <= 1) {
            return false;
        }

        // TODO: Hier können weitere Prüfungen hinzugefügt werden
        // z.B. ob die Version in Rechnungen verwendet wird
        
        return true;
    }

    /**
     * Holt die Anzahl der Versionen für diesen Client
     */
    public function getVersionCount()
    {
        $parentId = $this->parent_client_id ?? $this->id;
        
        return self::where(function($query) use ($parentId) {
                    $query->where('id', $parentId)
                          ->orWhere('parent_client_id', $parentId);
                })->count();
    }

    /**
     * Vergleicht diese Version mit der vorherigen und gibt Änderungen zurück
     */
    public function getChangesFromPreviousVersion()
    {
        $parentId = $this->parent_client_id ?? $this->id;
        
        // Finde die vorherige Version
        $previousVersion = self::where(function($query) use ($parentId) {
                                $query->where('id', $parentId)
                                      ->orWhere('parent_client_id', $parentId);
                            })
                            ->where('version', '<', $this->version)
                            ->orderBy('version', 'desc')
                            ->first();

        if (!$previousVersion) {
            return ['type' => 'initial', 'message' => 'Erste Version erstellt'];
        }

        return $this->compareVersions($previousVersion, $this);
    }

    /**
     * Vergleicht zwei Versionen und gibt die Unterschiede zurück
     */
    private function compareVersions($oldVersion, $newVersion)
    {
        $changes = [];
        $importantFields = [
            'clientname' => 'Name',
            'companyname' => 'Firma',
            'business' => 'Firmenart',
            'address' => 'Adresse',
            'postalcode' => 'PLZ',
            'location' => 'Ort',
            'email' => 'E-Mail',
            'phone' => 'Telefon',
            'vat_number' => 'UID',
            'tax_number' => 'Steuernummer',
            'webpage' => 'Webseite',
            'bank' => 'Bank',
            'accountnumber' => 'Kontonummer',
            'bic' => 'BIC',
            'smallbusiness' => 'Kleinunternehmer',
            'logo' => 'Logo',
            'signature' => 'Signatur',
            'style' => 'Stil',
            'max_upload_size' => 'Max. Upload-Größe',
            'company_registration_number' => 'Firmenbuchnummer',
            'management' => 'Geschäftsführung',
            'regional_court' => 'Handelsregistergericht',
            'color' => 'Farbe',
            'invoice_prefix' => 'Rechnungs-Präfix',
            'offer_prefix' => 'Angebots-Präfix'
        ];

        foreach ($importantFields as $field => $label) {
            $oldValue = $oldVersion->{$field};
            $newValue = $newVersion->{$field};

            // Spezielle Behandlung für boolean-Felder
            if ($field === 'smallbusiness') {
                $oldValue = $oldValue ? 'Ja' : 'Nein';
                $newValue = $newValue ? 'Ja' : 'Nein';
            }

            // Vergleiche Werte
            if ($oldValue != $newValue) {
                $changes[] = [
                    'field' => $field,
                    'label' => $label,
                    'old_value' => $oldValue,
                    'new_value' => $newValue,
                    'type' => $this->getChangeType($oldValue, $newValue)
                ];
            }
        }

        return [
            'type' => 'changes',
            'count' => count($changes),
            'changes' => $changes
        ];
    }

    /**
     * Bestimmt den Typ der Änderung
     */
    private function getChangeType($oldValue, $newValue)
    {
        if (empty($oldValue) && !empty($newValue)) {
            return 'added';
        } elseif (!empty($oldValue) && empty($newValue)) {
            return 'removed';
        } else {
            return 'modified';
        }
    }

    /**
     * Formatiert einen Wert für die Anzeige
     */
    public function formatValueForDisplay($value, $field = null)
    {
        if (is_null($value) || $value === '') {
            return '<em class="text-gray-400">Leer</em>';
        }

        // Spezielle Formatierung für bestimmte Felder
        switch ($field) {
            case 'logo':
                return $value ? 'Datei: ' . $value : '<em class="text-gray-400">Kein Logo</em>';
            case 'signature':
                return strlen($value) > 50 ? substr($value, 0, 50) . '...' : $value;
            case 'color':
                return $value ? '<span class="inline-block w-4 h-4 rounded border" style="background-color: ' . $value . '"></span> ' . $value : '<em class="text-gray-400">Keine Farbe</em>';
            default:
                return htmlspecialchars($value);
        }
    }
}
