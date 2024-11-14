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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username',100);
            $table->string('normalizedusername',100)->nullable();
            $table->string('password',1000);
            $table->string('name',100);
            $table->string('lastname',100);
            $table->string('email')->unique();
            $table->foreignId('role_id')->constrained('roles');
            $table->boolean('isactive')->default(1);
            $table->foreignId('client_id')->constrained('clients');
            $table->timestamp('email_verified_at')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
