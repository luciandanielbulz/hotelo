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
        Schema::table('categories', function (Blueprint $table) {
            $table->integer('billing_duration_years')->default(1)->after('is_active')->comment('Verrechnungsdauer in Jahren');
            $table->decimal('percentage', 5, 2)->default(100.00)->after('billing_duration_years')->comment('Prozentsatz (100 = 100%)');
            $table->enum('type', ['income', 'expense'])->default('expense')->after('percentage')->comment('Einnahmen oder Ausgaben');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['billing_duration_years', 'percentage', 'type']);
        });
    }
};
