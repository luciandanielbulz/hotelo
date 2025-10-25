<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // 0 Entwurf, 1 Offen, 2 Gesendet, 3 Teilweise bezahlt, 4 Bezahlt, 5 Überfällig, 6 Storniert
            $table->unsignedTinyInteger('status')->default(0)->after('archiveddate');
            $table->index('status');
        });

        // Grobe Initialisierung: Bezahlt -> 2, sonst Offen (1)
        try {
            // Heuristik: bereits bezahlt -> 4, sonst offen -> 1
            DB::table('invoices')->where('payed', 1)->update(['status' => 4]);
            DB::table('invoices')->whereNull('payed')->orWhere('payed', 0)->update(['status' => 1]);
        } catch (\Throwable $e) {
            // Falls Spalten nicht existieren oder DB abweicht, Fehlertoleranz: kein Abbruch
        }
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'status')) {
                $table->dropIndex(['status']);
                $table->dropColumn('status');
            }
        });
    }
};


