<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class AgentCollecte extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'agents'; // Correspond au nom de la table dans votre migration

    protected $fillable = [
        'nom_agent',
        'prenom_agent',
        'mail_agent',
        'mdp_agent',
        'date_create_agent',
        'date_del_agent',
    ];

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
