<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'sender_id',
        'sender_type',
        'receiver_id',
        'receiver_type',
        'content',
        'is_read',
    ];

    /**
     * Relation polymorphe avec l'expéditeur du message.
     */
    public function sender()
    {
        return $this->morphTo();
    }

    /**
     * Relation polymorphe avec le destinataire du message.
     */
    public function receiver()
    {
        return $this->morphTo();
    }
}
