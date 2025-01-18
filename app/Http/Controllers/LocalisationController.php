<?php

namespace App\Http\Controllers;

use App\Models\Localisation;
use Illuminate\Http\Request;

class LocalisationController extends Controller
{
    /**
     * Enregistre une nouvelle localisation dans la base de données.
     */
    public function storelocation(Request $request)
    {
        // Validation des données entrantes
        $validated = $request->validate([
            'location' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'client_id' => 'required|exists:clients,id', // Vérifie que client_id existe dans la table clients
        ]);

        // Création de la localisation
        $localisation = Localisation::create($validated);

        // Retourner une réponse JSON
        return response()->json([
            'message' => 'Localisation enregistrée avec succès',
            'localisation' => $localisation
        ], 201);
    }
}
