<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'agent_id', 'admin_id', 'type'];

    /**
     * Relation avec le client (s'il s'agit d'une conversation client-admin)
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Relation avec l'agent (s'il s'agit d'une conversation agent-admin)
     */
    public function agent()
    {
        return $this->belongsTo(AgentCollecte::class, 'agent_id');
    }

    /**
     * Relation avec l'administrateur
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /**
     * Relation avec les messages de la conversation
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
