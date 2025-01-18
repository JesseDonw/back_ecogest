<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tache extends Model
{
    use HasFactory;

    protected $table = 'taches';

    protected $fillable = [
        'description',
        'statut',
        'date_envoie_tache',
        'date_effectuer_tache',
        'zone',
    ];

    public function agents_collecte()
    {
        return $this->belongsTo(AgentCollecte::class);
    }

    public function maisons()
    {
        return $this->belongsTo(Maison::class);
    }
}
