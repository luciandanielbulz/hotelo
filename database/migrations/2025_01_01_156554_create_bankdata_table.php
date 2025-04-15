<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bankdata', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('partnername');
            $table->string('partneriban')->nullable();
            $table->string('partnerbic')->nullable();
            $table->string('partneracount')->nullable();
            $table->string('partnerroutingnumber')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3);
            $table->text('reference')->nullable();
            $table->string('referencenumber')->nullable();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->enum('category', [1, 2]);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bankdata');
    }
}; 