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
        Schema::create('invoice_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('filepath'); // Pfad zur gespeicherten PDF
            $table->date('invoice_date'); // Rechnungsdatum
            $table->text('description')->nullable(); // optionale Beschreibung
            $table->string('invoice_number')->nullable(); // optionale Rechnungsnummer
            $table->string('invoice_vendor')->nullable(); // optionale Rechnungsnummer
            $table->foreignId('client_id')->constrained('clients');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoiceupload');
    }
};
