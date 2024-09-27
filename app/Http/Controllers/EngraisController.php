<?php

namespace App\Http\Controllers;

use App\Models\Engrais;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEngraisRequest;
use App\Http\Requests\UpdateEngraisRequest;

class EngraisController extends Controller
{
    
    // Afficher tous les engrais
    public function index()
    {
        $engrais = Engrais::all();
        return response()->json($engrais);
    }

// Ajouter un nouvel engrais
public function store(Request $request)
{
    // Validation des données d'entrée
    $validated = $request->validate([
        'nom' => 'required|string|max:255',
        'image' => 'required|url',  // Si l'image est un lien
    ]);

    try {
        // Création de la nouvelle entrée d'engrais
        $engrais = Engrais::create($validated);

        return response()->json([
            'message' => 'Engrais ajouté avec succès.',
            'engrais' => $engrais,
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Erreur lors de l\'ajout de l\'engrais.',
            'details' => $e->getMessage(),
        ], 500);
    }
}


    // Afficher un engrais spécifique
    public function show($id)
    {
        try {
            $engrais = Engrais::findOrFail($id);
            return response()->json($engrais);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Engrais non trouvé : ' . $e->getMessage()], 404);
        }
    }

    // Mettre à jour un engrais
 // Mettre à jour un engrais existant
public function update(Request $request, $id)
{
    // Validation des données d'entrée
    $validated = $request->validate([
        'nom' => 'sometimes|required|string|max:255',
        'image' => 'sometimes|url',  // Si l'image est un lien
    ]);

    try {
        // Trouver l'engrais par ID
        $engrais = Engrais::findOrFail($id);

        // Mettre à jour l'engrais avec les données validées
        $engrais->update($validated);

        return response()->json([
            'message' => 'Engrais mis à jour avec succès.',
            'engrais' => $engrais,
        ]);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            'error' => 'Engrais non trouvé.',
        ], 404);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Erreur lors de la mise à jour de l\'engrais.',
            'details' => $e->getMessage(),
        ], 500);
    }
}

    // Supprimer un engrais
    public function destroy($id)
    {
        try {
            $engrais = Engrais::findOrFail($id);
            $engrais->delete();
            return response()->json(['message' => 'Engrais supprimé avec succès']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la suppression de l\'engrais : ' . $e->getMessage()], 500);
        }
    }
}
