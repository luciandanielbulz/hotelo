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
            $table->string('invoice_prefix', 10)->nullable()->after('color')->comment('Präfix für Rechnungsnummern (z.B. "R-", "RECH-")');
            $table->string('offer_prefix', 10)->nullable()->after('invoice_prefix')->comment('Präfix für Angebotsnummern (z.B. "A-", "ANG-")');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['invoice_prefix', 'offer_prefix']);
        });
    }
};
