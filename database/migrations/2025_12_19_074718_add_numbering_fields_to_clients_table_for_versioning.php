<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Füge Nummernformate und Präfixe hinzu für Versionierung
            if (!Schema::hasColumn('clients', 'invoice_number_format')) {
                $table->string('invoice_number_format', 50)->nullable()->after('color')->comment('Format für Rechnungsnummern (versioniert)');
            }
            if (!Schema::hasColumn('clients', 'offer_number_format')) {
                $table->string('offer_number_format', 50)->nullable()->after('invoice_number_format')->comment('Format für Angebotsnummern (versioniert)');
            }
            if (!Schema::hasColumn('clients', 'invoice_prefix')) {
                $table->string('invoice_prefix', 10)->nullable()->after('offer_number_format')->comment('Präfix für Rechnungsnummern (versioniert)');
            }
            if (!Schema::hasColumn('clients', 'offer_prefix')) {
                $table->string('offer_prefix', 10)->nullable()->after('invoice_prefix')->comment('Präfix für Angebotsnummern (versioniert)');
            }
        });
        
        // Migriere Daten von ClientSettings zu Clients (nur für aktive Versionen)
        DB::statement("
            UPDATE clients c
            INNER JOIN client_settings cs ON cs.client_id = COALESCE(c.parent_client_id, c.id)
            SET 
                c.invoice_number_format = cs.invoice_number_format,
                c.offer_number_format = cs.offer_number_format,
                c.invoice_prefix = cs.invoice_prefix,
                c.offer_prefix = cs.offer_prefix
            WHERE c.is_active = 1
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'invoice_number_format',
                'offer_number_format',
                'invoice_prefix',
                'offer_prefix'
            ]);
        });
    }
};
