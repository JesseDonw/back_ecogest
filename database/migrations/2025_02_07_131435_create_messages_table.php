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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->string('sender_type'); // Peut être 'App\Models\Administrateur' ou 'App\Models\Client'
            $table->unsignedBigInteger('receiver_id');
            $table->string('receiver_type'); // Peut être 'App\Models\Administrateur' ou 'App\Models\Client'
            $table->text('content');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            // Index pour optimiser les requêtes
            $table->index(['sender_id', 'sender_type']);
            $table->index(['receiver_id', 'receiver_type']);
        });
    }

    /**
     * Annuler les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
