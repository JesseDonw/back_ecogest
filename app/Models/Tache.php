<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tache extends Model
{
    use HasFactory;

    protected $table = 'taches';

    protected $fillable = [
        'nom_tache', // ✅ Nom de la tâche
        'date_envoie_tache', // ✅ Date de la tâche
        'localisation_id', // ✅ Localisation associée
        'statut',
    ];

    /**
     * ✅ Relation avec la localisation.
     */
    public function localisation()
    {
        return $this->belongsTo(Localisation::class);
    }

    public function agents_collecte()
    {
        return $this->belongsTo(AgentCollecte::class);
    }
}
