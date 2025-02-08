<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    // Démarrer une conversation avec un client ou un agent
    public function startConversation(Request $request)
    {
        $conversation = Conversation::firstOrCreate([
            'type' => $request->type,  // 'client' ou 'agent'
            'client_id' => $request->type === 'client' ? $request->participant_id : null,
            'agent_id'  => $request->type === 'agent' ? $request->participant_id : null,
            'admin_id'  => 2 // L'ID fixe de l'admin
        ]);

        return response()->json($conversation);
    }

    // Envoyer un message dans une conversation
    public function sendMessage(Request $request)
    {
        $message = Message::create([
            'conversation_id' => $request->conversation_id,
            'sender_id'       => $request->sender_id,
            'sender_type'     => $request->sender_type,  // 'admin', 'client', ou 'agent'
            'message'         => $request->message,
        ]);

        // Diffusion du message à tous les participants sauf l'émetteur
        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['message' => 'Message envoyé avec succès']);
    }

    // Récupérer les messages d'une conversation
    public function getMessages($conversationId)
    {
        $messages = Message::where('conversation_id', $conversationId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }
}
