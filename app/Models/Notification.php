<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'utilisateur_id',
        'message',
        'date',
    ];

    public function agents_collecte()
    {
        return $this->belongsTo(AgentCollecte::class);
    }
    public function clients()
    {
        return $this->belongsTo(Client::class);
    }
    public function administrateurs()
    {
        return $this->belongsTo(Administrateur::class);
    }
}
