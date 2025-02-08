<?php

namespace App\Http\Controllers;

use App\Models\Tache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $taches = Tache::with('localisation')->get();

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
}
