<?php

namespace App\Http\Controllers;

use App\Models\Tache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * ✅ Enregistre une nouvelle tâche dans la base de données avec un statut par défaut "en attente".
     */
    public function store(Request $request)
    {
        // 🔍 Validation des données entrantes
        $validator = Validator::make($request->all(), [
            'nom_tache' => 'required|string|max:255', // ✅ Nom de la tâche obligatoire
            'date_envoie_tache' => 'required|date', // ✅ Date obligatoire
            'localisation_id' => 'required|exists:localisation,id', // Vérifie que la localisation existe
            'statut' => 'sometimes|string|in:en attente,en cours,terminée', // ✅ Optionnel, valeurs autorisées
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // ✅ Création de la tâche avec "statut" par défaut à "en attente"
        $tache = Tache::create([
            'nom_tache' => $request->nom_tache,
            'date_envoie_tache' => $request->date_envoie_tache,
            'localisation_id' => $request->localisation_id,
            'statut' => $request->statut ?? 'en attente', // ✅ Défaut "en attente" si non fourni
        ]);

        return response()->json([
            'message' => 'Tâche enregistrée avec succès',
            'tache' => $tache
        ], 201);
    }

    /**
     * ✅ Récupère toutes les tâches enregistrées.
     */
    public function getAll()
    {
        $taches = Tache::with('localisation')->get();  // ✅ Récupère toutes les tâches avec leur localisation

        if ($taches->isEmpty()) {
            return response()->json(['message' => 'Aucune tâche trouvée'], 404);
        }

        return response()->json($taches, 200);
    }



    /**
     * ✅ Récupère une tâche spécifique par son ID.
     */
    public function getById($id)
    {
        $tache = Tache::with('localisation')->find($id);

        if (!$tache) {
            return response()->json(['message' => 'Tâche non trouvée'], 404);
        }

        return response()->json($tache, 200);
    }

    /**
     * ✅ Met à jour une tâche existante, y compris son statut.
     */
    public function updateTache(Request $request, $id)
    {
        $tache = Tache::find($id);

        if (!$tache) {
            return response()->json(['message' => 'Tâche non trouvée'], 404);
        }

        // 🔍 Validation des nouvelles données
        $validator = Validator::make($request->all(), [
            'nom_tache' => 'sometimes|string|max:255',
            'date_envoie_tache' => 'sometimes|date',
            'localisation_id' => 'sometimes|exists:localisation,id',
            'statut' => 'sometimes|string|in:en attente,en cours,terminée', // ✅ Statut modifiable
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // ✅ Mise à jour des données
        $tache->update($request->all());

        return response()->json([
            'message' => 'Tâche mise à jour avec succès',
            'tache' => $tache
        ], 200);
    }

    /**
     * ✅ Supprime une tâche.
     */
    public function deleteTache($id)
    {
        $tache = Tache::find($id);

        if (!$tache) {
            return response()->json(['message' => 'Tâche non trouvée'], 404);
        }

        $tache->delete();

        return response()->json(['message' => 'Tâche supprimée avec succès'], 200);
    }


      // ✅ Récupérer toutes les tâches avec ccomme status en attente
      public function getPendingTasks()
      {
          $pendingTasks = Tache::with('localisation')->where('statut', 'en attente')->get();

          if ($pendingTasks->isEmpty()) {
              return response()->json(['message' => 'Aucune tâche en attente trouvée'], 404);
          }

          return response()->json($pendingTasks, 200);
      }

      /**
 * ✅ Met à jour le statut d'une tâche en 'terminée' après validation sur la carte.
 */
public function validateTache($id)
{
    $tache = Tache::find($id);

    if (!$tache) {
        return response()->json(['message' => 'Tâche non trouvée'], 404);
    }

    $tache->statut = 'terminée';  // ✅ Changement de statut
    $tache->save();  // ✅ Sauvegarde dans la base de données

    return response()->json([
        'message' => 'Tâche validée avec succès',
        'tache' => $tache
    ], 200);
}


/**
 * ✅ Récupère toutes les tâches triées par localisation (ville, quartier, etc.).
 */
public function getTasksSortedByLocation()
{
    $taches = Tache::with('localisation')
        ->join('localisation', 'taches.localisation_id', '=', 'localisation.id')
        ->orderBy('localisation.location', 'asc')  // Trie les tâches par localisation (ville/quartier)
        ->select('taches.*')  // Sélectionne uniquement les colonnes des tâches
        ->get();

    if ($taches->isEmpty()) {
        return response()->json(['message' => 'Aucune tâche trouvée'], 404);
    }

    return response()->json($taches, 200);
}
/**
 * ✅ Récupère les tâches selon un statut spécifique (en attente, en cours, terminée).
 */
public function getTasksByStatus($status)
{
    if (!in_array($status, ['en attente', 'en cours', 'terminée'])) {
        return response()->json(['message' => 'Statut non valide'], 400);
    }

    $tasks = Tache::with('localisation')->where('statut', $status)->get();

    if ($tasks->isEmpty()) {
        return response()->json(['message' => "Aucune tâche avec le statut '{$status}' trouvée"], 404);
    }

    return response()->json($tasks, 200);
}

/**
 * ✅ Récupère les tâches les plus proches de l'utilisateur basé sur les coordonnées GPS.
 */
public function getNearestTasks(Request $request)
{
    $validator = Validator::make($request->all(), [
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    $latitude = $request->latitude;
    $longitude = $request->longitude;

    // Calcul de la distance avec la formule Haversine pour trier les tâches par proximité
    $tasks = Tache::selectRaw(
            'taches.*, localisation.latitude, localisation.longitude,
            (6371 * acos(cos(radians(?)) * cos(radians(localisation.latitude)) *
            cos(radians(localisation.longitude) - radians(?)) +
            sin(radians(?)) * sin(radians(localisation.latitude)))) AS distance',
            [$latitude, $longitude, $latitude]
        )
        ->join('localisation', 'taches.localisation_id', '=', 'localisation.id')
        ->orderBy('distance', 'asc')
        ->get();

    if ($tasks->isEmpty()) {
        return response()->json(['message' => 'Aucune tâche trouvée près de votre position'], 404);
    }

    return response()->json($tasks, 200);
}



public function getDoneTasks(Request $request)
{
    $agent = Auth::user();  // ✅ Vérifie si l'agent est bien authentifié

    if (!$agent) {
        return response()->json(['message' => 'Utilisateur non authentifié'], 401);
    }

    $tasks = Tache::where('agent_id', $agent->id) // ✅ Vérifie que 'agent_id' existe bien
                  ->where('statut', 'terminée')
                  ->with('localisation')
                  ->get();

    return response()->json($tasks, 200);
}





}
