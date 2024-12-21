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
        Schema::create('logos', function (Blueprint $table) {
            $table->id(); // Primärschlüssel
            $table->string('name')->comment('Name des Logos');
            $table->string('filename')->comment('Name der Datei');
            $table->string('localfilename')->comment('Name der Datei');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->timestamps(); // Erstellt automatisch created_at und updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logos');
    }
};
