<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Client extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'nom_client',
        'prenom_client',
        'mail_client',
        'mdp_client',
        'date_create_client',
        'date_del_client',
    ];

    // Pour Laravel Auth (si tu utilises Sanctum ou autre)
    public function getAuthPassword()
    {
        return $this->mdp_client;
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
