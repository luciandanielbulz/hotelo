<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Aktualisiere alle bestehenden Bankdaten basierend auf dem Betrag
        // Positiver Betrag = Einnahmen, Negativer Betrag = Ausgaben
        DB::table('bankdata')
            ->whereNull('type')
            ->orWhere('type', '')
            ->update([
                'type' => DB::raw('CASE WHEN amount >= 0 THEN "income" ELSE "expense" END')
            ]);

        // Log der Änderungen
        $updatedCount = DB::table('bankdata')
            ->whereRaw('CASE WHEN amount >= 0 THEN "income" ELSE "expense" END = type')
            ->count();

        \Log::info("Bankdaten-Typen automatisch aktualisiert", [
            'updated_records' => $updatedCount,
            'total_records' => DB::table('bankdata')->count()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Setze alle Typen zurück auf 'expense' (Standard)
        DB::table('bankdata')->update(['type' => 'expense']);
    }
};
