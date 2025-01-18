<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Localisation extends Model
{
    use HasFactory;

    protected $table = 'localisation';

    protected $fillable = [
        'location',
        'latitude',
        'longitude',
        'client_id',
    ];

    public function clients()
    {
        return $this->hasOne(Client::class);
    }

}