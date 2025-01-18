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
        Schema::create('taches', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->string('statut')->default('en attente');
            $table->dateTime('date_envoie_tache')->nullable();
            $table->dateTime('date_effectuer_tache')->nullable();
            $table->string('zone');
            $table->foreignId('maison_id')->constrained('maisons')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taches');
    }
};
