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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('clientname',100)->nullable();
            $table->string('companyname',100)->nullable();
            $table->string('business',60)->nullable();
            $table->string('address',100)->nullable();
            $table->string('postalcode',10)->nullable();
            $table->string('location',30)->nullable();
            $table->string('email',100)->nullable();
            $table->string('phone',40)->nullable();
            $table->foreignid('tax_id')->constrained('taxrates');
            $table->string('webpage',300)->nullable();
            $table->string('bank',30)->nullable();
            $table->string('accountnumber',30)->nullable();
            $table->string('vat_number',15)->nullable();
            $table->string('bic',30)->nullable();
            $table->boolean('smallbusiness')->default(false);
            $table->string('logo',100)->nullable();
            $table->string('logoheight',100)->nullable();
            $table->string('logowidth',100)->nullable();
            $table->string('signature',500)->nullable();
            $table->string('style',11)->nullable()->default(1);
            $table->integer('lastoffer');
            $table->integer('offermultiplikator');
            $table->integer('lastinvoice');
            $table->integer('invoicemultiplikator');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
