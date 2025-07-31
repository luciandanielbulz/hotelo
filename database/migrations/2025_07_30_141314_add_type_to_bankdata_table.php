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
            $table->enum('type', ['income', 'expense'])->default('expense')->after('category_id')->comment('Einnahmen oder Ausgaben');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bankdata', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
