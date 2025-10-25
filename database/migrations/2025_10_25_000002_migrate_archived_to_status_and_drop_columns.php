<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Überführen: archived => status = 7 (Archiviert)
        try {
            DB::table('invoices')->where('archived', 1)->update(['status' => 7]);
        } catch (\Throwable $e) {
            // still proceed to drop columns; ignore if not present
        }

        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'archived')) {
                $table->dropColumn('archived');
            }
            if (Schema::hasColumn('invoices', 'archiveddate')) {
                $table->dropColumn('archiveddate');
            }
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'archived')) {
                $table->boolean('archived')->default(false)->after('status');
            }
            if (!Schema::hasColumn('invoices', 'archiveddate')) {
                $table->timestamp('archiveddate')->nullable()->after('archived');
            }
        });

        try {
            DB::table('invoices')->where('status', 7)->update(['archived' => 1]);
        } catch (\Throwable $e) {
        }
    }
};


