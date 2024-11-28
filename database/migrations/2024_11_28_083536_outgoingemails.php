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
        Schema::create('outgoingemails', function (Blueprint $table) {
            $table->id(); // Primärschlüssel
            $table->integer('type')->comment('E-Mail-Typ (z. B. 1 = Rechnung, 2 = Angebot)')->required();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->integer('objectnumber')->comment('Referenznummer des zugehörigen Objekts');
            $table->datetime('sentdate')->comment('Datum, an dem die E-Mail gesendet wurde');
            $table->string('getteremail')->comment('Empfängeremail');
            $table->string('filename')->comment('Name der angehängten Datei oder des E-Mail-Protokolls');
            $table->boolean('withattachment')->comment('Gibt an, ob ein Anhang vorhanden ist (true/false)');
            $table->boolean('status')->comment('Status der E-Mail (z. B. 0 = fehlgeschlagen, 1 = gesendet)');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->timestamps(); // Erstellt automatisch created_at und updated_at
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
