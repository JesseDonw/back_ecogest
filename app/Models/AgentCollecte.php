<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class AgentCollecte extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'agents'; // Correspond au nom de la table

    protected $fillable = [
        'nom_agent',
        'prenom_agent',
        'mail_agent',
        'mdp_agent',
        'photo_agent',
        'date_create_agent',
        'statut_agent',
    ];

    // ✅ Relation entre AgentCollecte et Tache
    public function taches()
    {
        return $this->hasMany(Tache::class, 'agent_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }
}
