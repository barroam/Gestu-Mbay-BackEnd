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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $equipement = Equipement::create($validated);
            return response()->json($equipement, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de l\'ajout de l\'équipement : ' . $e->getMessage()], 500);
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
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nom' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $equipement = Equipement::findOrFail($id);
            $equipement->update($validated);
            return response()->json($equipement);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la mise à jour de l\'équipement : ' . $e->getMessage()], 500);
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
