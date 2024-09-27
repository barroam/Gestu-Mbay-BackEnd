<?php

namespace App\Http\Controllers;

use App\Models\AvisProjet;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAvisProjetRequest;
use App\Http\Requests\UpdateAvisProjetRequest;

class AvisProjetController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'projet_id' => 'required|exists:projets,id',
            'user_id' => 'required|exists:users,id',
            'titre' => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ]);

        $avis = AvisProjet::create($validated);

        return response()->json([
            'message' => 'Avis de projet créé avec succès.',
            'data' => $avis // Retourne les données de l'avis directement
        ], 201);
    }

    // READ (Liste)
    public function index()
    {
        $avis = AvisProjet::with(['projet', 'user'])->get();
        return response()->json([
            'message' => 'Liste des avis récupérée avec succès.',
            'data' => $avis // Retourne la liste des avis directement
        ]);
    }

    // READ (Détail)
    public function show($id)
    {
        $avis = AvisProjet::with(['projet', 'user'])->find($id);

        if (!$avis) {
            return response()->json(['error' => 'Avis non trouvé.'], 404);
        }

        return response()->json([
            'message' => 'Avis récupéré avec succès.',
            'data' => $avis // Retourne les données de l'avis directement
        ]);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $avis = AvisProjet::find($id);

        if (!$avis) {
            return response()->json(['error' => 'Avis non trouvé.'], 404);
        }

        $validated = $request->validate([
            'titre' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string|max:500',
        ]);

        $avis->update($validated);

        return response()->json([
            'message' => 'Avis mis à jour avec succès.',
            'data' => $avis // Retourne les données mises à jour de l'avis
        ]);
    }

    // DELETE
    public function destroy($id)
    {
        $avis = AvisProjet::find($id);

        if (!$avis) {
            return response()->json(['error' => 'Avis non trouvé.'], 404);
        }

        $avis->delete();

        return response()->json(['message' => 'Avis supprimé avec succès.']);
    }
}
