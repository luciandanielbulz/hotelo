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
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('client_version_id')
                  ->nullable()
                  ->after('customer_id')
                  ->constrained('clients')
                  ->nullOnDelete()
                  ->comment('Referenz zur Client-Version, die zum Zeitpunkt der Rechnungserstellung gültig war');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['client_version_id']);
            $table->dropColumn('client_version_id');
        });
    }
};
