<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgentCollecte;
use App\Models\Client;
use App\Models\Administrateur;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    // Inscription d'un agent
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nom_agent' => 'required|string|max:255',
                'prenom_agent' => 'required|string|max:255',
                'mail_agent' => 'required|email|unique:agents,mail_agent',
                'mdp_agent' => 'required|string|min:8',
                'photo_agent' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $photoPath = null;

            if ($request->hasFile('photo_agent')) {
                $file = $request->file('photo_agent');
                $allowedExtensions = ['jpg', 'jpeg', 'png'];
                if (!in_array($file->getClientOriginalExtension(), $allowedExtensions)) {
                    return response()->json(['error' => 'L\'image doit être au format JPG, JPEG ou PNG uniquement.'], 400);
                }
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $photoPath = $file->storeAs('agents_photos', $fileName, 'public');
            }

            $agent = AgentCollecte::create([
                'nom_agent' => $request->nom_agent,
                'prenom_agent' => $request->prenom_agent,
                'mail_agent' => $request->mail_agent,
                'mdp_agent' => Hash::make($request->mdp_agent),
                'photo_agent' => $photoPath,
                'date_create_agent' => now(),
            ]);

            $token = $agent->createToken('API Token')->plainTextToken;
            $photoUrl = $photoPath ? asset('storage/' . $photoPath) : null;

            return response()->json([
                'message' => 'Agent créé avec succès',
                'agent' => $agent,
                'photo_url' => $photoUrl,
                'token' => $token
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function registercli(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom_client' => 'required|string|max:255',
            'prenom_client' => 'required|string|max:255',
            'mail_client' => 'required|email|unique:clients,mail_client',
            'mdp_client' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $client = Client::create([
            'nom_client' => $request->nom_client,
            'prenom_client' => $request->prenom_client,
            'mail_client' => $request->mail_client,
            'mdp_client' => Hash::make($request->mdp_client),
        ]);

        $token = $client->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'Inscription réussie',
            'client_id' => $client->id,
            'token' => $token
        ], 201);
    }


    public function registeradmin(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'nom_admin' => 'required|string|max:255',
            'prenom_admin' => 'required|string|max:255',
            'mail_admin' => 'required|email|unique:administrateurs,mail_admin',
            'mdp_admin' => 'required|string|min:8',
            'photo_admin' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',  // Ajout de la validation de la photo
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $photoPath = null;

        // Gestion de l'upload de la photo si elle est fournie
        if ($request->hasFile('photo_admin')) {
            $file = $request->file('photo_admin');
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            if (!in_array($file->getClientOriginalExtension(), $allowedExtensions)) {
                return response()->json(['error' => 'L\'image doit être au format JPG, JPEG ou PNG uniquement.'], 400);
            }
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $photoPath = $file->storeAs('admin_photos', $fileName, 'public');
        }

        // Création de l'administrateur
        $admin = Administrateur::create([
            'nom_admin' => $request->nom_admin,
            'prenom_admin' => $request->prenom_admin,
            'mail_admin' => $request->mail_admin,
            'mdp_admin' => Hash::make($request->mdp_admin),
            'photo_admin' => $photoPath,
            'date_create_admin' => now(),
            'statut_admin' => 'actif',
        ]);

        // Génération du token d'authentification
        $token = $admin->createToken('API Token')->plainTextToken;
        $photoUrl = $photoPath ? asset('storage/' . $photoPath) : null;

        // Retour de la réponse JSON
        return response()->json([
            'message' => 'Administrateur créé avec succès',
            'admin' => $admin,
            'photo_url' => $photoUrl,
            'token' => $token
        ], 201);

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}



    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mail_agent' => 'required|email',
            'mdp_agent' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $agent = AgentCollecte::where('mail_agent', $request->mail_agent)->first();

        if ($agent && Hash::check($request->mdp_agent, $agent->mdp_agent)) {
            $token = $agent->createToken('API Token')->plainTextToken;
            return response()->json([
                'message' => 'Connexion réussie',
                'token' => $token,
                'agent_id' => $agent->id  // Ajoute l'ID de l'agent ici
            ], 200);
        }


        return response()->json(['message' => 'Identifiants incorrects'], 401);
    }

    public function logincli(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mail_client' => 'required|email',
            'mdp_client' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $client = Client::where('mail_client', $request->mail_client)->first();

        if ($client && Hash::check($request->mdp_client, $client->mdp_client)) {
            $token = $client->createToken('API Token')->plainTextToken;
            return response()->json([
                'message' => 'Connexion réussie',
                'token' => $token,
                'client_id' => $client->id,
            ], 200);
        }

        return response()->json(['message' => 'Identifiants incorrects'], 401);
    }

    public function loginadmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mail_admin' => 'required|email',
            'mdp_admin' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $admin = Administrateur::where('mail_admin', $request->mail_admin)->first();

        if ($admin && Hash::check($request->mdp_admin, $admin->mdp_admin)) {
            $token = $admin->createToken('API Token')->plainTextToken;  // Crée un nouveau token à la connexion
            return response()->json([
                'message' => 'Connexion réussie',
                'token' => $token,
                'admin' => $admin,
            ], 200);
        }

        return response()->json(['message' => 'Identifiants incorrects'], 401);
    }


    // Suppression d'un Agent Collecte
    public function deleteAgent($id)
    {
        $agent = AgentCollecte::find($id);

        if (!$agent) {
            return response()->json(['message' => 'Agent non trouvé'], 404);
        }

        $agent->delete();

        return response()->json(['message' => 'Agent supprimé avec succès'], 200);
    }

    public function getAllAgents()
    {
        $agents = AgentCollecte::all();

        if ($agents->isEmpty()) {
            return response()->json(['message' => 'Aucun agent trouvé'], 404);
        }

        return response()->json($agents, 200);
    }

    public function getAgent($id)
    {
        $agent = AgentCollecte::find($id);
        if (!$agent) {
            return response()->json(['message' => 'Agent non trouvé'], 404);
        }
        return response()->json($agent, 200);
    }

    public function getAllClients()
    {
        $clients = Client::all();

        if ($clients->isEmpty()) {
            return response()->json(['message' => 'Aucun client trouvé'], 404);
        }

        return response()->json($clients, 200);
    }

    public function getClient($id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json(['message' => 'Client non trouvé'], 404);
        }

        return response()->json($client, 200);
    }

    public function getAdmins($id)
    {
        $admin = Administrateur::find($id);
        if (!$admin) {
            return response()->json(['message' => 'Administrateur non trouvé'], 404);
        }
        return response()->json($admin, 200);
    }

    public function getAllAdmin()
    {
        try {
            $admins = Administrateur::all();  // Récupère tous les administrateurs
            return response()->json($admins, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des administrateurs', 'details' => $e->getMessage()], 500);
        }
    }


    public function updateAgent(Request $request, $id)
    {
        $agent = AgentCollecte::find($id);

        if (!$agent) {
            return response()->json(['message' => 'Agent non trouvé'], 404);
        }

        $agent->update($request->all());

        return response()->json(['message' => 'Profil mis à jour avec succès', 'agent' => $agent]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Déconnexion réussie'], 200);
    }
}
