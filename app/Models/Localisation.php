<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Localisation extends Model
{
    use HasFactory;

    // 🔥 Force Laravel à utiliser "localisation" au lieu de "localisations"
    protected $table = 'localisation';

    // 🔹 Ajout du champ 'zone' dans les propriétés mass assignable
    protected $fillable = [
        'location',   // Ville + Pays
        'latitude',
        'longitude',
        'client_id',
    ];

    // 🔹 Relation avec le modèle Client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
