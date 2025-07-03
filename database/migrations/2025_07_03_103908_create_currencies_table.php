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
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3); // EUR, USD, etc.
            $table->string('name'); // Euro, US-Dollar, etc.
            $table->string('symbol', 10); // €, $, etc.
            $table->decimal('exchange_rate', 10, 4)->default(1.0000); // Wechselkurs zur Basiswährung
            $table->boolean('is_default')->default(false); // Standard-Währung für Client
            $table->foreignId('client_id')->constrained('clients');
            $table->timestamps();
            
            // Ein Client kann pro Währungscode nur eine Währung haben
            $table->unique(['client_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
