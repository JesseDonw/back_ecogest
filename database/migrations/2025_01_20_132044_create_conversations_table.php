<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter les migrations.
     */
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();

            // Identifiant du client (nullable si conversation avec un agent)
            $table->unsignedBigInteger('client_id')->nullable();

            // Identifiant de l'agent (nullable si conversation avec un client)
            $table->unsignedBigInteger('agent_id')->nullable();

            // Identifiant de l'administrateur (facultatif si conversation spécifique admin)
            $table->unsignedBigInteger('admin_id')->nullable();

            // Type de conversation : 'client' ou 'agent'
            $table->string('type')->default('client');

            $table->timestamps();

            // Clés étrangères
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('administrateurs')->onDelete('cascade');
        });
    }

    /**
     * Annuler les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
