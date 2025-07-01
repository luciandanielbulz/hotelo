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
        Schema::create('client_settings', function (Blueprint $table) {
            $table->id();
            
            // Foreign Key zur ursprünglichen Client-ID (nicht zu Versionen)
            $table->unsignedBigInteger('client_id')->unique(); // Ein Setting-Record pro Client
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            
            // Statische Nummerierungsfelder
            $table->integer('lastinvoice')->default(0);
            $table->integer('lastoffer')->default(0);
            $table->integer('invoicemultiplikator')->default(1);
            $table->integer('offermultiplikator')->default(1);
            $table->string('invoice_number_format', 50)->nullable();
            $table->string('offer_number_format', 50)->nullable();
            
            // Andere globale Einstellungen können hier hinzugefügt werden
            // z.B. Standardsteuersätze, globale Konfigurationen, etc.
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_settings');
    }
};
