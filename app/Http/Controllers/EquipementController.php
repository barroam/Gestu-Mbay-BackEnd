<?php

namespace App\Http\Controllers;

use App\Models\Equipement;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEquipementRequest;
use App\Http\Requests\UpdateEquipementRequest;

class EquipementController extends Controller
{
    // Afficher tous les équipements
    public function index()
    {
        $equipements = Equipement::all();
        return response()->json($equipements);
    }

    // Ajouter un nouvel équipement
  // Ajouter un nouvel équipement
public function store(Request $request)
{
    // Validation des données d'entrée
    $validated = $request->validate([
        'nom' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'required|url', // Assurez-vous que c'est une URL valide
    ]);

    try {
        // Vérification si l'équipement existe déjà
        $existingEquipement = Equipement::where('nom', $validated['nom'])->first();
        if ($existingEquipement) {
            return response()->json([
                'error' => 'Un équipement avec ce nom existe déjà.',
            ], 409); // Conflit
        }

        // Création de la nouvelle instance d'équipement
        $equipement = Equipement::create($validated);

        return response()->json([
            'message' => 'Équipement ajouté avec succès.',
            'equipement' => $equipement,
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Erreur lors de l\'ajout de l\'équipement.',
            'details' => $e->getMessage(),
        ], 500);
    }
}


    // Afficher un équipement spécifique
    public function show($id)
    {
        try {
            $equipement = Equipement::findOrFail($id);
            return response()->json($equipement);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Équipement non trouvé : ' . $e->getMessage()], 404);
        }
    }

    // Mettre à jour un équipement
    // Mettre à jour un équipement existant
public function update(Request $request, $id)
{
    // Validation des données d'entrée
    $validated = $request->validate([
        'nom' => 'sometimes|required|string|max:255',
        'description' => 'sometimes|required|string',
        'image' => 'sometimes|url', // Vérifiez que c'est une URL valide
    ]);

    try {
        // Rechercher l'équipement par son ID
        $equipement = Equipement::findOrFail($id);

        // Vérification de l'existence d'un autre équipement avec le même nom
        if (isset($validated['nom']) && $validated['nom'] !== $equipement->nom) {
            $existingEquipement = Equipement::where('nom', $validated['nom'])->first();
            if ($existingEquipement) {
                return response()->json([
                    'error' => 'Un équipement avec ce nom existe déjà.',
                ], 409); // Conflit
            }
        }

        // Mise à jour de l'équipement
        $equipement->update($validated);

        return response()->json([
            'message' => 'Équipement mis à jour avec succès.',
            'equipement' => $equipement,
        ]);
    } catch (ModelNotFoundException $e) {
        return response()->json([
            'error' => 'Équipement non trouvé.',
        ], 404); // Not Found
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Erreur lors de la mise à jour de l\'équipement.',
            'details' => $e->getMessage(),
        ], 500);
    }
}


    // Supprimer un équipement
    public function destroy($id)
    {
        try {
            $equipement = Equipement::findOrFail($id);
            $equipement->delete();
            return response()->json(['message' => 'Équipement supprimé avec succès']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la suppression de l\'équipement : ' . $e->getMessage()], 500);
        }
    }
}
