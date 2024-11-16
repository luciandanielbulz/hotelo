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
        Schema::create('invoicepositions', function (Blueprint $table) {
            $table->id();  // Primärschlüssel
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');  // Fremdschlüssel zu Rechnungen, mit Cascade-Löschung
            $table->decimal('amount', 10, 2);  // Menge, Dezimalzahl mit bis zu 10 Stellen und 2 Nachkommastellen
            $table->string('designation', 255)->nullable();  // Bezeichnung, varchar mit maximal 255 Zeichen
            $table->string('details', 2000)->nullable();  // Details, varchar mit maximal 2000 Zeichen, optional
            $table->foreignId('unit_id')->default(1)->constrained('units');  // Fremdschlüssel zu Einheiten, Standardwert 1
            $table->decimal('price', 10, 2);  // Preis, Dezimalzahl mit bis zu 10 Stellen und 2 Nachkommastellen
            $table->text('positiontext')->nullable();  // Positionstext, optional
            $table->boolean('positiontextoption')->default(false);  // Option für Positionstext, standardmäßig false
            $table->integer('sequence')->nullable();  // Sequenznummer, optional
            $table->boolean('issoftdeleted')->default(false);  // Soft-Delete-Flag, standardmäßig false
            $table->timestamps();  // Standard timestamps für created_at und updated_at
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoicepositions');
    }
};
