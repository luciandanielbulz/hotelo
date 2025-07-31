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
        Schema::table('bankdata', function (Blueprint $table) {
            // Entferne die alte category enum Spalte, falls sie existiert
            if (Schema::hasColumn('bankdata', 'category')) {
                $table->dropColumn('category');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bankdata', function (Blueprint $table) {
            // Füge die alte category Spalte wieder hinzu
            $table->enum('category', ['1', '2'])->default('1')->after('client_id');
        });
    }
};
