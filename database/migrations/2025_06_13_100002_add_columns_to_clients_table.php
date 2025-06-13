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
        Schema::table('clients', function (Blueprint $table) {
            $table->string('company_registration_number')->nullable()->comment('Firmenbuchnummer');
            $table->string('tax_number')->nullable()->comment('Steuernummer');
            $table->string('management')->nullable()->comment('Geschäftsführung');
            $table->string('regional_court')->nullable()->comment('Landesgericht');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'company_registration_number',
                'tax_number',
                'management',
                'regional_court'
            ]);
        });
    }
};
