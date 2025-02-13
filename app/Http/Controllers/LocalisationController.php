<?php

namespace App\Http\Controllers;

use App\Models\Localisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocalisationController extends Controller
{
    /**
     * ✅ Enregistre une nouvelle localisation dans la base de données.
     */
    public function storeLocation(Request $request)
    {
        // 🔍 Validation des données entrantes
        $validator = Validator::make($request->all(), [
            'location' => 'required|string|max:255', // Ville + Pays
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'client_id' => 'required|exists:clients,id', // Vérifie que le client existe
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // ✅ Création de la localisation
        $localisation = Localisation::create([
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'client_id' => $request->client_id,
        ]);

        return response()->json([
            'message' => 'Localisation enregistrée avec succès',
            'localisation' => $localisation
        ], 201);
    }

    /**
     * ✅ Récupère toutes les localisations enregistrées.
     */
    public function getAll()
    {
        $localisations = Localisation::all();

        if ($localisations->isEmpty()) {
            return response()->json(['message' => 'Aucune localisation trouvée'], 404);
        }

        return response()->json($localisations, 200);
    }

    /**
     * ✅ Récupère une localisation spécifique par son ID.
     */
    public function getById($id)
    {
        $localisation = Localisation::find($id);

        if (!$localisation) {
            return response()->json(['message' => 'Localisation non trouvée'], 404);
        }

        return response()->json($localisation, 200);
    }

    /**
     * ✅ Met à jour une localisation existante.
     */
    public function updateLocation(Request $request, $id)
    {
        $localisation = Localisation::find($id);

        if (!$localisation) {
            return response()->json(['message' => 'Localisation non trouvée'], 404);
        }

        // 🔍 Validation des nouvelles données
        $validator = Validator::make($request->all(), [
            'location' => 'sometimes|string|max:255',
            'latitude' => 'sometimes|numeric',
            'longitude' => 'sometimes|numeric',
            'client_id' => 'sometimes|exists:clients,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // ✅ Mise à jour des données
        $localisation->update($request->all());

        return response()->json([
            'message' => 'Localisation mise à jour avec succès',
            'localisation' => $localisation
        ], 200);
    }

    /**
     * ✅ Supprime une localisation.
     */
    public function deleteLocation($id)
    {
        $localisation = Localisation::find($id);

        if (!$localisation) {
            return response()->json(['message' => 'Localisation non trouvée'], 404);
        }

        $localisation->delete();

        return response()->json(['message' => 'Localisation supprimée avec succès'], 200);
    }
}
