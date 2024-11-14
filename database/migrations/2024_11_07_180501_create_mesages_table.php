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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();  // Primärschlüssel
            $table->string('type', 50)->nullable();  // Typ des E-Mails, kann null sein
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');  // Fremdschlüssel zu Kunden
            $table->integer('objectnumber')->nullable();  // Objekt-Nummer, kann null sein
            $table->timestamp('senddate')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));  // Sendedatum, Standard ist aktueller Zeitstempel
            $table->string('filename', 60)->nullable();  // Dateiname, kann null sein
            $table->boolean('withattachment')->nullable();  // Mit Anhang, kann null sein
            $table->integer('status')->nullable();  // Status, kann null sein
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');  // Fremdschlüssel zu Clients
            $table->string('recipientmail', 100)->nullable();  // E-Mail des Empfängers, kann null sein
            $table->timestamps();  // Erstellungs- und Aktualisierungs-Timestamps
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
