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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('companyname',100)->nullable();
            $table->string('customername',100)->nullable();
            $table->string('address',100)->nullable();
            $table->string('postalcode',10)->nullable();
            $table->string('location',100)->nullable();
            $table->string('country',100)->nullable();
            $table->string('phone',100)->nullable();
            $table->string('fax',100)->nullable();
            $table->string('email',100)->nullable();
            $table->string('vat_number')->nullable();
            $table->foreignId('tax_id')->constrained('taxrates');
            $table->foreignId('condition_id')->constrained('conditions');
            $table->foreignId('salutation_id')->constrained('salutations');
            $table->string('title',50)->nullable();
            $table->boolean('active')->default(false);
            $table->string('emailsubject',200)->nullable();
            $table->string('emailbody',1000)->nullable();
            $table->boolean('issoftdeleted')->default(false);
            $table->foreignId('client_id')->constrained('clients');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
