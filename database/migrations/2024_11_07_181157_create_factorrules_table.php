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
        Schema::create('factorrules', function (Blueprint $table) {
            $table->id();  // Primärschlüssel
            $table->string('descriptionpattern', 255);  // Beschreibungsmuster, varchar mit maximal 255 Zeichen
            $table->decimal('factor', 5, 2);  // Faktor im Dezimalformat mit 5 Stellen, davon 2 Nachkommastellen
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');  // Fremdschlüssel zu Clients, löscht zugehörige Einträge bei Löschung des Clients
            $table->timestamps();  // Erstellungs- und Aktualisierungs-Timestamps
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factorrules');
    }
};
