<?php

namespace App\Http\Controllers;
use App\Models\AgentCollecte;
use App\Models\Message;
use App\Models\Client;
use App\Models\Administrateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class MessageController extends Controller
{

    public function sendmessage(Request $request)
    {
        Log::info('sendMessage a été appelé');

        try {
            $user = Auth::user();
            if (!$user) {
                Log::error('Utilisateur non authentifié');
                return response()->json(['message' => 'Utilisateur non authentifié'], 401);
            }

            Log::info('Création du message');
            $message = Message::create([
                'sender_id' => $request->sender_id,
                'sender_type' => $request->sender_type,
                'receiver_id' => $request->receiver_id,
                'receiver_type' => $request->receiver_type,
                'content' => $request->content,
                'is_read' => false,
            ]);

            Log::info('Message envoyé avec succès');
            return response()->json([
                'message' => 'Message envoyé avec succès',
                'data' => $message
            ], 201);

        } catch (\Exception $e) {
            Log::error('Erreur serveur : ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur serveur',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getMessages(Request $request)
    {
        $request->validate([
            'with_id' => 'required|integer',
            'with_type' => 'required|string|in:App\Models\Client,App\Models\Administrateur,App\Models\AgentCollecte',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non authentifié'], 401);
        }

        $messages = Message::where(function ($query) use ($user, $request) {
            $query->where('sender_id', $user->id)
                  ->where('sender_type', get_class($user))
                  ->where('receiver_id', $request->with_id)
                  ->where('receiver_type', $request->with_type);
        })->orWhere(function ($query) use ($user, $request) {
            $query->where('receiver_id', $user->id)
                  ->where('receiver_type', get_class($user))
                  ->where('sender_id', $request->with_id)
                  ->where('sender_type', $request->with_type);
        })->orderBy('created_at', 'asc')->get();

        return response()->json($messages);
    }


    public function markAsRead($id)
    {
        $message = Message::findOrFail($id);
        $user = Auth::user();

        // Vérifie que l'utilisateur est bien le destinataire du message
        if ($message->receiver_id === $user->id && $message->receiver_type === get_class($user)) {
            $message->is_read = true;
            $message->save();
            return response()->json(['message' => 'Message marqué comme lu.']);
        }

        return response()->json(['error' => 'Action non autorisée.'], 403);
    }

    /**
     * Supprimer un message (optionnel).
     */
    public function destroy($id)
    {
        $message = Message::findOrFail($id);
        $user = Auth::user();

        // Autoriser la suppression seulement si l'utilisateur est l'expéditeur
        if ($message->sender_id === $user->id && $message->sender_type === get_class($user)) {
            $message->delete();
            return response()->json(['message' => 'Message supprimé.']);
        }

        return response()->json(['error' => 'Action non autorisée.'], 403);
    }
}
