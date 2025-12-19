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
        Schema::table('offers', function (Blueprint $table) {
            // Ändere die number Spalte von integer zu string
            // Zuerst als varchar temporär hinzufügen, dann Daten migrieren, dann alte Spalte löschen
            $table->string('number', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            // Konvertiere zurück zu integer (nur wenn möglich)
            // Achtung: Dies kann Datenverlust verursachen, wenn Präfixe vorhanden sind
            $table->integer('number')->change();
        });
    }
};
