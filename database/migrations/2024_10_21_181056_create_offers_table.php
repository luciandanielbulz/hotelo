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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->date('date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('number');
            $table->string('description',200)->nullable();
            $table->foreignId('tax_id')->constrained('taxrates');
            $table->double('taxburden')->nullable();
            $table->foreignId('condition_id')->constrained('conditions');
            $table->boolean('archived')->default(false);
            $table->dateTime('archiveddate')->nullable();
            $table->string('comment',200)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offerpositions');
        Schema::dropIfExists('offers');
    }
};
