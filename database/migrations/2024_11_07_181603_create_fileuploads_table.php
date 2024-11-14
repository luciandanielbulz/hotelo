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
        Schema::create('fileuploads', function (Blueprint $table) {
            $table->id();  // Primärschlüssel
            $table->string('filename', 300);  // Name der hochgeladenen Datei, maximal 300 Zeichen
            $table->string('filetempname', 300);  // Temporärer Name der Datei, maximal 300 Zeichen
            $table->string('filesize', 200);  // Größe der Datei, maximal 200 Zeichen
            $table->string('fileerrors', 300)->nullable();  // Fehler beim Hochladen, maximal 300 Zeichen, optional
            $table->dateTime('date')->default(DB::raw('CURRENT_TIMESTAMP'));  // Hochladedatum, Standard ist aktueller Zeitstempel
            $table->boolean('processed')->default(false);  // Verarbeitungsstatus, Standard auf false
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');  // Fremdschlüssel zu Clients, löscht zugehörige Einträge bei Löschung des Clients
            $table->timestamps();  // Erstellungs- und Aktualisierungs-Timestamps
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fileuploads');
    }
};
