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
        // Entferne Präfix- und Upload-Felder die jetzt in client_settings gespeichert werden
        $columnsToDrop = [];
        
        if (Schema::hasColumn('clients', 'invoice_prefix')) {
            $columnsToDrop[] = 'invoice_prefix';
        }
        
        if (Schema::hasColumn('clients', 'offer_prefix')) {
            $columnsToDrop[] = 'offer_prefix';
        }
        
        if (Schema::hasColumn('clients', 'max_upload_size')) {
            $columnsToDrop[] = 'max_upload_size';
        }
        
        if (!empty($columnsToDrop)) {
            Schema::table('clients', function (Blueprint $table) use ($columnsToDrop) {
                $table->dropColumn($columnsToDrop);
            });
        }
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
