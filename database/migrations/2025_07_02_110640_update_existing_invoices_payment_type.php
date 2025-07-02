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
        // Nur ausführen wenn payment_type Spalte existiert
        if (Schema::hasColumn('invoice_uploads', 'payment_type')) {
            // Aktualisiere alle bestehenden Datensätze ohne payment_type
            DB::table('invoice_uploads')
                ->whereNull('payment_type')
                ->update(['payment_type' => 'elektronisch']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Keine Rückgängig-Aktion erforderlich
        // Die Daten bleiben bestehen
    }
};
