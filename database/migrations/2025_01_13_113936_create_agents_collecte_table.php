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
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('nom_agent');
            $table->string('prenom_agent');
            $table->string('mail_agent')->unique();
            $table->string('mdp_agent');
            $table->string('photo_agent')->nullable();
            $table->dateTime('date_create_agent')->default(now());
            $table->dateTime('date_del_agent')->nullable();
            $table->timestamps(); // created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
