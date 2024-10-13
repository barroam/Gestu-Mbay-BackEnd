<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ControleDemande;

class ControleDemandeController extends Controller
{
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'numero_parcelle' => 'required|integer',
            'hectare' => 'required|numeric|between:0,9999.99',
            'culture' => 'required|in:vivriere,rente,horticole,industrielle,perenne',
        ]);

        try {
            $controleDemande = ControleDemande::create($validated);
            return response()->json([
                'message' => 'Demande créée avec succès.',
                'data' => [
                    'id' => $controleDemande->id, // Ajout de l'ID dans la réponse
                    'numero_parcelle' => $controleDemande->numero_parcelle,
                    'hectare' => $controleDemande->hectare,
                    'culture' => $controleDemande->culture,
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la création de la demande.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    // Récupération de toutes les demandes
    public function index()
    {
        $demandes = ControleDemande::all();
        return response()->json($demandes, 200);
    }

    // Récupération d'une demande par ID
    public function show($id)
    {
        try {
            $controleDemande = ControleDemande::findOrFail($id);
            return response()->json($controleDemande, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Demande non trouvée.',
                'details' => $e->getMessage()
            ], 404);
        }
    }

    // Mise à jour d'une demande
    public function update(Request $request, $id)
    {
        // Validation des données
        $validated = $request->validate([
            'numero_parcelle' => 'required|integer',
            'hectare' => 'required|numeric|between:0,9999.99',
            'culture' => 'required|in:vivriere,rente,horticole,industrielle,perenne',
        ]);

        try {
            $controleDemande = ControleDemande::findOrFail($id);
            $controleDemande->update($validated);
            return response()->json([
                'message' => 'Demande mise à jour avec succès.',
                'data' => [
                    'id' => $controleDemande->id, // Ajout de l'ID dans la réponse
                    'numero_parcelle' => $controleDemande->numero_parcelle,
                    'hectare' => $controleDemande->hectare,
                    'culture' => $controleDemande->culture,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la mise à jour de la demande.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    // Suppression d'une demande
    public function destroy($id)
    {
        try {
            $controleDemande = ControleDemande::findOrFail($id);
            $controleDemande->delete();
            return response()->json([
                'message' => 'Demande supprimée avec succès.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la suppression de la demande.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
 