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
            $table->string('nom_tache'); // ✅ Nom de la tâche obligatoire
            $table->date('date_envoie_tache'); // ✅ Date obligatoire
            $table->string('statut');

            // 🔥 Associe la tâche à une localisation existante
            $table->foreignId('localisation_id')->constrained('localisation')->onDelete('cascade');

            // 🔥 Associe la tâche à un agent de collecte
            $table->foreignId('agent_id')->nullable()->constrained('agents')->onDelete('set null');

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
