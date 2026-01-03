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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique()->comment('Ländername');
            $table->string('name_de', 100)->nullable()->comment('Ländername auf Deutsch');
            $table->string('iso_code', 2)->unique()->comment('ISO 3166-1 alpha-2 Code (z.B. AT, DE, FR)');
            $table->string('iso_code_3', 3)->nullable()->comment('ISO 3166-1 alpha-3 Code (z.B. AUT, DEU, FRA)');
            $table->string('phone_code', 10)->nullable()->comment('Telefonvorwahl (z.B. +43, +49)');
            $table->string('currency_code', 3)->default('EUR')->comment('Währungscode (ISO 4217)');
            $table->boolean('is_eu_member')->default(true)->comment('Ist EU-Mitglied');
            $table->integer('sort_order')->default(0)->comment('Sortierreihenfolge');
            $table->boolean('is_active')->default(true)->comment('Aktiv');
            $table->timestamps();
            
            $table->index('iso_code');
            $table->index('is_eu_member');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
