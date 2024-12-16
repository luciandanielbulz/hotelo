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
        Schema::create('bankdata', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('partnername')->nullable();
            $table->string('partneriban')->nullable();
            $table->string('partnerbic')->nullable();
            $table->double('amount')->nullable();
            $table->string('currency')->nullable();
            $table->string('reference',1000)->nullable();
            $table->string('referencenumber',400)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bankdata');
    }
};
