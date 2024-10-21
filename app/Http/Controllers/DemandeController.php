<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Demande;
use App\Models\Ressource;
use App\Models\InfoDemande;
use Illuminate\Http\Request;
use App\Models\ControleDemande;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreDemandeRequest;
use App\Http\Requests\UpdateDemandeRequest;

class DemandeController extends Controller
{
    public function store(Request $request)
{
    // Validation des données
    try {
        $validated = $request->validate([
            'statut' => 'required|in:en_attente,approuvee,refusee',
            'ressource_id' => 'required|exists:ressources,id',
            'controle_demande_id' => 'required|exists:controle_demandes,id',
            'info_demande_id' => 'required|exists:info_demandes,id',
            'user_id' => 'required|exists:users,id',
            'titre' => 'required|string|max:255',
        ]);

        // Log des données validées
        Log::info('Données validées pour la création de la demande : ', $validated);

        // Créer la demande
        $demande = Demande::create($validated);

        // Vérifiez si la demande a bien été créée
        if (!$demande) {
            throw new \Exception("La demande n'a pas pu être créée.");
        }

        return response()->json([
            'message' => 'Demande créée avec succès.',
            'data' => $demande
        ], 201);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'error' => 'Erreur de validation.',
            'details' => $e->errors() // Détails des erreurs de validation
        ], 422);
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
        $demandes = Demande::with(['ressource', 'controleDemande', 'infoDemande', 'user'])->get();
        return response()->json($demandes, 200);
    }

    // Récupération d'une demande par ID
    public function show($id)
    {
        try {
            $demande = Demande::with(['ressource', 'controleDemande', 'infoDemande', 'user'])->findOrFail($id);
            return response()->json($demande, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Demande non trouvée.',
                'details' => $e->getMessage()
            ], 404);
        }
    }

public function update(Request $request, Demande $demande)
{
    // Validation des données
    $validated = $request->validate([
        'statut' => 'required|in:en_attente,approuvee,refusee',
        'ressource_id' => 'required|exists:ressources,id',
        'controle_demande_id' => 'required|exists:controle_demandes,id',
        'info_demande_id' => 'required|exists:info_demandes,id',
        'user_id' => 'required|exists:users,id',
        'titre' => 'required|string|max:255',
    ]);

    // Vérifiez si les IDs existent dans la base de données
    $ressourceExists = Ressource::find($request->ressource_id);
    if (!$ressourceExists) {
        return response()->json([
            'error' => 'Ressource ID non valide.',
            'details' => 'La ressource spécifiée n\'existe pas.',
        ], 400);
    }

    $controleDemandeExists = ControleDemande::find($request->controle_demande_id);
    if (!$controleDemandeExists) {
        return response()->json([
            'error' => 'Contrôle de demande ID non valide.',
            'details' => 'Le contrôle de demande spécifié n\'existe pas.',
        ], 400);
    }

    $infoDemandeExists = InfoDemande::find($request->info_demande_id);
    if (!$infoDemandeExists) {
        return response()->json([
            'error' => 'Info demande ID non valide.',
            'details' => 'L\'information de demande spécifiée n\'existe pas.',
        ], 400);
    }

    $userExists = User::find($request->user_id);
    if (!$userExists) {
        return response()->json([
            'error' => 'User ID non valide.',
            'details' => 'L\'utilisateur spécifié n\'existe pas.',
        ], 400);
    }

    try {
        // Mettez à jour la demande
        $demande->update($validated);

        return response()->json([
            'message' => 'Demande mise à jour avec succès.',
            'data' => $demande
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Erreur lors de la mise à jour de la demande.',
            'details' => $e->getMessage()
        ], 500);
    }
}

public function updateStatus(Request $request, Demande $demande)
{
    // Validation des données
    $validated = $request->validate([
        'statut' => 'required|in:en_attente,approuvee,refusee', // Validation du champ statut
    ]);

    try {
        // Mise à jour uniquement du statut de la demande
        $demande->statut = $validated['statut'];
        $demande->save(); // Enregistrement des modifications

        // Retourner une réponse JSON avec un message de succès et les données mises à jour
        return response()->json([
            'message' => 'Statut de la demande mis à jour avec succès.',
            'data' => $demande
        ], 200); // Statut HTTP 200 pour succès
    } catch (\Exception $e) {
        // Retourner une réponse JSON avec un message d'erreur en cas d'exception
        return response()->json([
            'error' => 'Erreur lors de la mise à jour du statut de la demande.',
            'details' => $e->getMessage()
        ], 500); // Statut HTTP 500 pour erreur interne
    }
}

public function destroy($id)
{
    try {
        $demande = Demande::findOrFail($id);
        $demande->delete();

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

//demande par utilisateur
public function getDemandesByUser($userId)
{
    try {
        // Récupérer les demandes de l'utilisateur avec l'ID spécifié
        $demandes = Demande::with(['ressource', 'controleDemande', 'infoDemande'])
                            ->where('user_id', $userId)
                            ->get();

        // Vérifiez si des demandes existent pour cet utilisateur
        if ($demandes->isEmpty()) {
            return response()->json([
                'message' => 'Aucune demande trouvée pour cet utilisateur.'
            ], 404);
        }

        return response()->json($demandes, 200);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Erreur lors de la récupération des demandes.',
            'details' => $e->getMessage()
        ], 500);
    }
}

}
