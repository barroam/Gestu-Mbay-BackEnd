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
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $engrais = Engrais::create($validated);
            return response()->json($engrais, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de l\'ajout de l\'engrais : ' . $e->getMessage()], 500);
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
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nom' => 'sometimes|required|string|max:255',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $engrais = Engrais::findOrFail($id);
            $engrais->update($validated);
            return response()->json($engrais);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la mise à jour de l\'engrais : ' . $e->getMessage()], 500);
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
