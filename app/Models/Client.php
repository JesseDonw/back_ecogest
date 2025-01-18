<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Client extends Model
{
    use HasFactory,HasApiTokens;

    protected $table = 'clients';

    protected $fillable = [
        'nom_client',
        'prenom_client',
        'mail_client',
        'mdp_client',
        'date_create_client',
        'date_del_client',
        
    ];

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
