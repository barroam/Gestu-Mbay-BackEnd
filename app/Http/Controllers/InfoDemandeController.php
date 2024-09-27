<?php

namespace App\Http\Controllers;

use App\Models\InfoDemande;
use Illuminate\Http\Request;
use App\Http\Requests\StoreInfoDemandeRequest;
use App\Http\Requests\UpdateInfoDemandeRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class InfoDemandeController extends Controller
{
 
    public function store(Request $request)
    {
        $validated = $request->validate([
            'demandeur' => 'required|in:individuel,groupe,association',
            'nom_demandeur' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'cin/ninea' => [
                'required',
                function ($attribute, $value, $fail) {
                    // Validation pour NINEA ou CNI
                    if (!preg_match('/^\d{12}$|^\d{14}-D\d{3}$/', $value)) {
                        $fail('Le champ CIN/NINEA doit être au format CNI (14 chiffres et D suivi de 3 chiffres) ou NINEA (12 chiffres).');
                    }
                }
            ],
            'contact' => 'required|regex:/^7[0-9]{8}$/', // Validation du contact
        ]);
    
        try {
            $infoDemande = InfoDemande::create($validated);
            return response()->json($infoDemande, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de l\'ajout de la demande : ' . $e->getMessage()], 500);
        }
    }
    
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'demandeur' => 'required|in:individuel,groupe,association',
            'nom_demandeur' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'cin/ninea' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^\d{12}$|^\d{14}-D\d{3}$/', $value)) {
                        $fail('Le champ CIN/NINEA doit être au format CNI (14 chiffres et D suivi de 3 chiffres) ou NINEA (12 chiffres).');
                    }
                }
            ],
            'contact' => 'required|regex:/^7[0-9]{8}$/', // Validation du contact
        ]);
    
        try {
            $infoDemande = InfoDemande::findOrFail($id);
            $infoDemande->update($validated);
    
            return response()->json($infoDemande, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la mise à jour de la demande : ' . $e->getMessage()], 500);
        }
    }
    

    // Récupérer la liste des demandes
    public function index()
    {
        try {
            $infoDemandes = InfoDemande::all();
            return response()->json($infoDemandes, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la récupération des demandes.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    // Récupérer une demande spécifique par ID
    public function show($id)
    {
        try {
            $infoDemande = InfoDemande::findOrFail($id);
            return response()->json($infoDemande, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Demande non trouvée.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la récupération de la demande.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
{
    try {
        // Trouver la demande par ID
        $infoDemande = InfoDemande::findOrFail($id);

        // Supprimer la demande
        $infoDemande->delete();

        // Retourner une réponse JSON avec un message de succès
        return response()->json([
            'message' => 'Demande supprimée avec succès.'
        ], 200);
    } catch (\Exception $e) {
        // En cas d'erreur, retourner une réponse JSON avec un message d'erreur
        return response()->json([
            'error' => 'Erreur lors de la suppression de la demande : ' . $e->getMessage()
        ], 500);
    }
}


}
