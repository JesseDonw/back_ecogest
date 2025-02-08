<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Localisation extends Model
{
    use HasFactory;

    // 🔥 Force Laravel à utiliser "localisation" au lieu de "localisations"
    protected $table = 'localisation';

    protected $fillable = [
        'location', // Ville + Pays
        'latitude',
        'longitude',
        'client_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
