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
        Schema::table('bankdata', function (Blueprint $table) {
            $table->string('transaction_id')->nullable()->after('id');
            $table->string('contained_transaction_id')->nullable()->after('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bankdata', function (Blueprint $table) {
            $table->dropColumn(['transaction_id', 'contained_transaction_id']);
        });
    }
};
