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
            // Versionierungs-Felder
            $table->timestamp('valid_from')->default(now())->after('updated_at');
            $table->timestamp('valid_to')->nullable()->after('valid_from');
            $table->boolean('is_active')->default(true)->after('valid_to');
            $table->unsignedBigInteger('parent_client_id')->nullable()->after('is_active');
            $table->integer('version')->default(1)->after('parent_client_id');
            
            // Index für bessere Performance
            $table->index(['is_active', 'valid_from', 'valid_to']);
            $table->index(['parent_client_id', 'version']);
            
            // Foreign Key für parent_client_id
            $table->foreign('parent_client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['parent_client_id']);
            $table->dropIndex(['is_active', 'valid_from', 'valid_to']);
            $table->dropIndex(['parent_client_id', 'version']);
            $table->dropColumn(['valid_from', 'valid_to', 'is_active', 'parent_client_id', 'version']);
        });
    }
};
