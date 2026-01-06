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
        Schema::table('conditions', function (Blueprint $table) {
            $table->integer('dunning_reminder_days')->nullable()->default(0)->after('daysnetto')->comment('Tage bis Erinnerung');
            $table->integer('dunning_first_stage_days')->nullable()->default(0)->after('dunning_reminder_days')->comment('Tage bis erste Mahnstufe');
            $table->integer('dunning_second_stage_days')->nullable()->default(0)->after('dunning_first_stage_days')->comment('Tage bis zweite Mahnstufe');
            $table->integer('dunning_third_stage_days')->nullable()->default(0)->after('dunning_second_stage_days')->comment('Tage bis dritte Mahnstufe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conditions', function (Blueprint $table) {
            $table->dropColumn([
                'dunning_reminder_days',
                'dunning_first_stage_days',
                'dunning_second_stage_days',
                'dunning_third_stage_days'
            ]);
        });
    }
};
