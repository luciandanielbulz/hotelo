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
        Schema::table('client_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('client_settings', 'zip_filename_template')) {
                $table->string('zip_filename_template', 255)->nullable()->after('max_upload_size')
                    ->comment('Template für ZIP-Dateinamen. Platzhalter: {date}, {index}, {vendor}, {invoice_number}, {category}');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_settings', function (Blueprint $table) {
            if (Schema::hasColumn('client_settings', 'zip_filename_template')) {
                $table->dropColumn('zip_filename_template');
            }
        });
    }
};
