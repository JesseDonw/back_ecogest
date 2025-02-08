<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    // Diffuser sur un canal privé spécifique à la conversation
    public function broadcastOn()
    {
        return new Channel('conversation.' . $this->message->conversation_id);
    }

    // Nom de l'événement diffusé (facultatif, sinon ce sera 'MessageSent')
    public function broadcastAs()
    {
        return 'message.sent';
    }
}
