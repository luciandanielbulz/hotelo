<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('outgoingemails', function (Blueprint $table) {
            // Ändere objectnumber von integer zu string, um längere Rechnungsnummern zu unterstützen
            $table->string('objectnumber', 100)->change()->comment('Referenznummer des zugehörigen Objekts (Rechnung/Angebot)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('outgoingemails', function (Blueprint $table) {
            // Zurück zu integer (falls ein Rollback nötig ist)
            $table->integer('objectnumber')->change()->comment('Referenznummer des zugehörigen Objekts');
        });
    }
};
