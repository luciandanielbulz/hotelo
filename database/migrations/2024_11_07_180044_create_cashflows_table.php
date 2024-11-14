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
        Schema::create('cashflows', function (Blueprint $table) {
            $table->id();
            $table->date('transactiondate');
            $table->string('partnername', 255)->nullable();
            $table->string('partneriban', 34)->nullable();
            $table->string('bic_swift', 11)->nullable();
            $table->string('partneracountnumber', 34)->nullable();
            $table->string('bankcode', 10)->nullable();
            $table->decimal('amount', 10, 2);
            $table->char('currency', 3);
            $table->text('transactiondetails')->nullable();
            $table->string('transactionreference', 255)->nullable();
            $table->string('ownaccountname', 255)->nullable();
            $table->string('owniban', 34)->nullable();
            $table->enum('paymentmethod', ['Überweisung', 'Kartenzahlung'])->default('Überweisung');
            $table->enum('transactiontype', ['Einnahme', 'Ausgabe', 'Info']);
            $table->timestamp('date')->useCurrent()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->foreignId('client_id')->constrained('clients');
            $table->double('factor')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashflows');
    }
};
