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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();  // Primärschlüssel
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');  // Fremdschlüssel zu Kunden, mit Cascade-Löschung
            $table->date('date')->default(DB::raw('CURRENT_TIMESTAMP'));  // Erstellungsdatum, Standard ist aktueller Zeitstempel
            $table->string('number', 255);  // Rechnungsnummer, varchar mit maximal 255 Zeichen
            $table->text('description')->nullable();  // Beschreibung, kann null sein
            $table->foreignId('tax_id')->constrained('taxrates')->onDelete('cascade');
            $table->boolean('taxburden')->nullable();  // Steuerlast, boolean, kann null sein
            $table->decimal('depositamount', 10, 2)->nullable();  // Anzahlungsbetrag, Dezimalzahl, kann null sein
            $table->date('periodfrom')->nullable();  // Anfang der Periode, kann null sein
            $table->date('periodto')->nullable();  // Ende der Periode, kann null sein
            $table->foreignId('condition_id')->constrained('conditions')->onDelete('cascade');  // Fremdschlüssel zu Konditionen, kann null sein
            $table->boolean('payed')->nullable();  // Bezahltstatus, boolean, kann null sein
            $table->dateTime('payeddate')->nullable();  // Bezahldatum, kann null sein
            $table->boolean('archived')->default(false);  // Archivstatus, boolean, Standard auf false
            $table->date('archiveddate')->nullable();  // Archivierungsdatum, kann null sein
            $table->text('comment')->nullable();  // Kommentar, kann null sein
            $table->dateTime('createddate')->default(DB::raw('CURRENT_TIMESTAMP'));  // Erstellungsdatum, Standard ist aktueller Zeitstempel
            $table->string('offer_id')->nullable();
            $table->timestamps();  // Standard timestamps für created_at und updated_at
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
