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
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedTinyInteger('dunning_stage')->nullable()->default(0)->after('status')->comment('0=keine, 1=Erinnerung, 2=1.Mahnung, 3=2.Mahnung, 4=3.Mahnung');
            $table->date('due_date')->nullable()->after('date')->comment('Fälligkeitsdatum');
            $table->date('dunning_stage_date')->nullable()->after('dunning_stage')->comment('Datum der letzten Mahnstufen-Berechnung');
            $table->index(['dunning_stage', 'due_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['dunning_stage', 'due_date']);
            $table->dropColumn(['dunning_stage', 'due_date', 'dunning_stage_date']);
        });
    }
};
