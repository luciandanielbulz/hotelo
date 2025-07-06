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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#6366f1'); // Hex color code
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('client_id');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            
            // Unique constraint for name per client
            $table->unique(['name', 'client_id']);
            
            // Index for performance
            $table->index('client_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
