<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgentCollecte;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    // Inscription d'un agent
    public function register(Request $request)
    {
        // Validation des données d'inscription
        $validator = Validator::make($request->all(), [
            'nom_agent' => 'required|string|max:255',
            'prenom_agent' => 'required|string|max:255',
            'mail_agent' => 'required|email|unique:agents,mail_agent',
            'mdp_agent' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Création de l'agent
        $agent = AgentCollecte::create([
            'nom_agent' => $request->nom_agent,
            'prenom_agent' => $request->prenom_agent,
            'mail_agent' => $request->mail_agent,
            'mdp_agent' => Hash::make($request->mdp_agent),
            'date_create_agent' => now(),
            'statut_agent' => 'actif',  // Par défaut, statut actif
        ]);

        // Retourner une réponse après la création
        return response()->json(['message' => 'agent créé avec succès', 'agent' => $agent], 201);
    }

    public function registercli(Request $request)
    {
        // Validation des données d'inscription
        $validator = Validator::make($request->all(), [
            'nom_client' => 'required|string|max:255',
            'prenom_client' => 'required|string|max:255',
            'mail_client' => 'required|email|unique:clients,mail_client',
            'mdp_client' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Création du client
        $client = Client::create([
            'nom_client' => $request->nom_client,
            'prenom_client' => $request->prenom_client,
            'mail_client' => $request->mail_client,
            'mdp_client' => Hash::make($request->mdp_client),
            'date_create_client' => now(),
            'statut_client' => 'actif',  // Par défaut, statut actif
        ]);

        // Retourner une réponse après la création
        return response()->json(['message' => 'client créé avec succès', 'client' => $client], 201);
    }

     
    // Connexion d'un agent
    public function login(Request $request)
    {
        // Validation des données de connexion
        $validator = Validator::make($request->all(), [
            'mail_agent' => 'required|email',
            'mdp_agent' => 'required|string|min:8',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    
        // Tentative de connexion
        $agent = AgentCollecte::where('mail_agent', $request->mail_agent)->first();
    
        // Vérifier si l'agent existe et si le mot de passe est correct
        if ($agent && Hash::check($request->mdp_agent, $agent->mdp_agent)) {
            // Créer un token API pour l'agent connecté
            $token = $agent->createToken('API Token')->plainTextToken;
    
            // Retourne une réponse avec le token d'authentification
            return response()->json(['message' => 'Connexion réussie', 'token' => $token], 200);
        }
    
        return response()->json(['message' => 'Identifiants incorrects'], 401);
    }
    
     // Connexion d'un client
    public function logincli(Request $request)
    {
        // Validation des données de connexion
        $validator = Validator::make($request->all(), [
            'mail_client' => 'required|email',
            'mdp_client' => 'required|string|min:8',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    
        // Tentative de connexion
        $client = Client ::where('mail_client', $request->mail_client)->first();
    
        // Vérifier si l'agent existe et si le mot de passe est correct
        if ($client && Hash::check($request->mdp_client, $client->mdp_client)) {
            // Créer un token API pour l'agent connecté
            $token = $client->createToken('API Token')->plainTextToken;
    
            // Retourne une réponse avec le token d'authentification
            return response()->json(['message' => 'Connexion réussie', 'token' => $token], 200);
        }
    
        return response()->json(['message' => 'Identifiants incorrects'], 401);
    }
    
    

    // Déconnexion de l'agent
    public function logout(Request $request)
    {
        Auth::logout();

        return response()->json(['message' => 'Déconnexion réussie'], 200);
    }
}
