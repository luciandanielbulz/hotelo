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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name',50)->notNull;
            $table->boolean('customerlist');
            $table->boolean('offerlist');
            $table->boolean('invoiceslist');
            $table->boolean('useradministration');
            $table->boolean('rolesadministration');
            $table->boolean('settings');
            $table->boolean('emaillist');
            $table->boolean('personalsettings');
            $table->boolean('salesanalysis');
            $table->boolean('clients');
            $table->boolean('cashflow');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
