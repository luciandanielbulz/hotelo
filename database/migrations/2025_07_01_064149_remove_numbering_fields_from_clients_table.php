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
        Schema::table('clients', function (Blueprint $table) {
            // Entferne Nummerierungsfelder die jetzt in client_settings gespeichert werden
            $table->dropColumn([
                'lastinvoice',
                'lastoffer',
                'invoicemultiplikator',
                'offermultiplikator',
                'invoice_number_format'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Wiederherstellen der entfernten Felder falls Migration rückgängig gemacht wird
            $table->integer('lastinvoice')->default(0);
            $table->integer('lastoffer')->default(0);
            $table->integer('invoicemultiplikator')->default(1000);
            $table->integer('offermultiplikator')->default(1000);
            $table->string('invoice_number_format')->default('YYYYNN');
        });
    }
};
