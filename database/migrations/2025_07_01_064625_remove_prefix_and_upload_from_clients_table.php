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
            // Entferne Präfix- und Upload-Felder die jetzt in client_settings gespeichert werden
            $table->dropColumn([
                'invoice_prefix',
                'offer_prefix',
                'max_upload_size'
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
            $table->string('invoice_prefix')->nullable();
            $table->string('offer_prefix')->nullable();
            $table->integer('max_upload_size')->default(2048);
        });
    }
};
