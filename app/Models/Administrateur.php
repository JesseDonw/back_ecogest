<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Administrateur extends Model
{
    use HasFactory,HasApiTokens;

    protected $table = 'administrateurs';

    protected $fillable = [
        'nom_admin',
        'prenom_admin',
        'mail_admin',
        'mdp_admin',
        'date_create_admin',
        'date_del_admin',
        
    ];

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }
}
