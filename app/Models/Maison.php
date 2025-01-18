<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maison extends Model
{
    use HasFactory;

    protected $table = 'maisons';

    protected $fillable = [
        'adresse',
        'statutCollecte',
    ];

    public function clients()
    {
        return $this->belongsTo(Client::class);
    }

    public function taches()
    {
        return $this->belongsTo(Tache::class);
    }
}
