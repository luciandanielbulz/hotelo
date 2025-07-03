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
        Schema::table('invoice_uploads', function (Blueprint $table) {
            $table->enum('type', ['income', 'expense'])->default('expense')->after('payment_type'); // Einnahme oder Ausgabe
            $table->enum('tax_type', ['gross', 'net'])->default('gross')->after('type'); // Brutto oder Netto
            $table->decimal('amount', 10, 2)->nullable()->after('tax_type'); // Preis/Betrag
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->onDelete('set null')->after('amount'); // Währung
            $table->decimal('net_amount', 10, 2)->nullable()->after('currency_id'); // Berechneter Nettobetrag
            $table->decimal('tax_amount', 10, 2)->nullable()->after('net_amount'); // Berechneter Steuerbetrag
            $table->decimal('tax_rate', 5, 2)->nullable()->after('tax_amount'); // Steuersatz in Prozent
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_uploads', function (Blueprint $table) {
            $table->dropConstrainedForeignId('currency_id');
            $table->dropColumn([
                'type',
                'tax_type', 
                'amount',
                'net_amount',
                'tax_amount',
                'tax_rate'
            ]);
        });
    }
};
