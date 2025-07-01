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
        // Felder zu client_settings hinzufügen
        Schema::table('client_settings', function (Blueprint $table) {
            $table->string('invoice_prefix')->nullable();
            $table->string('offer_prefix')->nullable();
            $table->integer('max_upload_size')->default(2048);
        });

        // Daten von clients zu client_settings migrieren
        DB::statement("
            UPDATE client_settings cs
            INNER JOIN clients c ON cs.client_id = c.id OR cs.client_id = c.parent_client_id
            SET 
                cs.invoice_prefix = c.invoice_prefix,
                cs.offer_prefix = c.offer_prefix,
                cs.max_upload_size = c.max_upload_size
            WHERE c.is_active = 1
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_settings', function (Blueprint $table) {
            $table->dropColumn(['invoice_prefix', 'offer_prefix', 'max_upload_size']);
        });
    }
};
