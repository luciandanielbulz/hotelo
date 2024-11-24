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
        Schema::create('offerpositions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_id')->constrained('offers');
            $table->decimal('amount');
            $table->string('designation',200)->nullable();
            $table->string('details',1000)->nullable();
            $table->foreignId('unit_id')->constrained('units');
            $table->decimal('price')->nullable()->default(0.0);
            $table->boolean('positiontext')->default(false);
            $table->integer('sequence')->default(0);
            $table->boolean('issoftdeleted')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('positions');
    }
};
