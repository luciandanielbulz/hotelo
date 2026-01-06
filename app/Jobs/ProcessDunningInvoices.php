<?php

namespace App\Jobs;

use App\Models\Invoices;
use App\Models\Condition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ProcessDunningInvoices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Die Client-ID, für die die Mahnungen verarbeitet werden sollen.
     */
    protected $clientId;

    /**
     * Create a new job instance.
     */
    public function __construct($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('ProcessDunningInvoices Job gestartet', ['client_id' => $this->clientId]);

        $today = Carbon::today();
        $processedCount = 0;
        $updatedCount = 0;

        // Nur Rechnungen mit Status: Offen (1), Gesendet (2), Teilweise bezahlt (3)
        // Nicht: Entwurf (0), Bezahlt (4), Überfällig (5), Storniert (6), Archiviert (7)
        // WICHTIG: Nur Rechnungen des spezifischen Clients verarbeiten
        $invoices = Invoices::where('client_version_id', $this->clientId)
            ->whereIn('status', [1, 2, 3]) // Nur Offen, Gesendet, Teilweise bezahlt
            ->whereNotNull('condition_id')
            ->whereNotNull('date')
            ->get();

        Log::info('Gefundene Rechnungen für Mahnwesen-Prüfung', ['count' => $invoices->count()]);

        foreach ($invoices as $invoice) {
            try {
                $condition = Condition::withTrashed()
                    ->where('id', $invoice->condition_id)
                    ->where('client_id', $this->clientId)
                    ->first();
                
                if (!$condition) {
                    Log::warning('Condition nicht gefunden für Rechnung oder gehört nicht zum Client', [
                        'invoice_id' => $invoice->id,
                        'condition_id' => $invoice->condition_id,
                        'client_id' => $this->clientId
                    ]);
                    continue;
                }

                // Fälligkeitsdatum berechnen: Rechnungsdatum + daysnetto
                $invoiceDate = Carbon::parse($invoice->date);
                $dueDate = $invoiceDate->copy()->addDays($condition->daysnetto ?? 0);
                
                // Tage seit Fälligkeit berechnen (positiv = überfällig, negativ = noch nicht fällig)
                // Wenn dueDate in der Vergangenheit liegt, ist die Rechnung überfällig
                $daysOverdue = $dueDate->isPast() ? $today->diffInDays($dueDate) : 0;
                
                // Mahnstufe bestimmen
                $dunningStage = 0;
                
                // Nur wenn überfällig (daysOverdue > 0)
                if ($daysOverdue > 0) {
                    // Prüfe Mahnstufen in absteigender Reihenfolge (höchste zuerst)
                    if ($condition->dunning_third_stage_days > 0 && $daysOverdue >= $condition->dunning_third_stage_days) {
                        $dunningStage = 4; // Dritte Mahnstufe
                    } elseif ($condition->dunning_second_stage_days > 0 && $daysOverdue >= $condition->dunning_second_stage_days) {
                        $dunningStage = 3; // Zweite Mahnstufe
                    } elseif ($condition->dunning_first_stage_days > 0 && $daysOverdue >= $condition->dunning_first_stage_days) {
                        $dunningStage = 2; // Erste Mahnstufe
                    } elseif ($condition->dunning_reminder_days > 0 && $daysOverdue >= $condition->dunning_reminder_days) {
                        $dunningStage = 1; // Erinnerung
                    }
                    // Wenn alle Mahnstufen-Tage 0 sind, bleibt dunningStage = 0 (Rechnung wird ignoriert)
                }

                // Update-Daten sammeln
                $updateData = [];
                $needsUpdate = false;

                // Mahnstufe aktualisieren wenn geändert
                if ($invoice->dunning_stage != $dunningStage) {
                    $updateData['dunning_stage'] = $dunningStage;
                    $needsUpdate = true;
                }

                // Due Date aktualisieren wenn geändert oder nicht gesetzt
                if (!$invoice->due_date || $invoice->due_date != $dueDate->format('Y-m-d')) {
                    $updateData['due_date'] = $dueDate->format('Y-m-d');
                    $needsUpdate = true;
                }

                // Immer dunning_stage_date aktualisieren (für tägliche Neuberechnung)
                $updateData['dunning_stage_date'] = $today->format('Y-m-d');
                
                // Immer aktualisieren wenn:
                // 1. Etwas geändert hat (needsUpdate = true)
                // 2. Due Date noch nicht gesetzt ist
                // 3. dunning_stage_date nicht heute ist (tägliche Neuberechnung)
                if ($needsUpdate || !$invoice->due_date || $invoice->dunning_stage_date != $today->format('Y-m-d')) {
                    $invoice->update($updateData);
                    $updatedCount++;
                    
                    Log::info('Rechnung Mahnstufe aktualisiert', [
                        'invoice_id' => $invoice->id,
                        'invoice_number' => $invoice->number,
                        'invoice_date' => $invoiceDate->format('Y-m-d'),
                        'condition_daysnetto' => $condition->daysnetto,
                        'condition_name' => $condition->conditionname,
                        'dunning_stage' => $dunningStage,
                        'due_date' => $dueDate->format('Y-m-d'),
                        'days_overdue' => $daysOverdue,
                        'today' => $today->format('Y-m-d'),
                        'dunning_reminder_days' => $condition->dunning_reminder_days,
                        'dunning_first_stage_days' => $condition->dunning_first_stage_days,
                        'dunning_second_stage_days' => $condition->dunning_second_stage_days,
                        'dunning_third_stage_days' => $condition->dunning_third_stage_days
                    ]);
                }

                $processedCount++;
            } catch (\Exception $e) {
                Log::error('Fehler beim Verarbeiten der Rechnung', [
                    'invoice_id' => $invoice->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        Log::info('ProcessDunningInvoices Job abgeschlossen', [
            'processed' => $processedCount,
            'updated' => $updatedCount
        ]);
    }
}
