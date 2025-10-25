<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Setze Status=2 (Gesendet) für alle Rechnungen, die im Postausgang existieren
        // outgoingemails.type: 1 = invoice (laut Code), objectnumber = invoices.number
        DB::statement('
            UPDATE invoices i
            INNER JOIN (
                SELECT oe.objectnumber
                FROM outgoingemails oe
                WHERE oe.type = 1
                GROUP BY oe.objectnumber
            ) s ON s.objectnumber = i.number
            SET i.status = CASE WHEN i.status = 4 THEN 4 ELSE 2 END
        ');
    }

    public function down(): void
    {
        // Down-Migration ohne Rücksetzung, um manuelle Stati nicht zu überschreiben
    }
};


