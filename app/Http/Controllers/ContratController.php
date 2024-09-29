<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Contrat;
use Illuminate\Http\Request;
use App\Models\HistoriqueContrat;
use App\Http\Requests\StoreContratRequest;
use App\Http\Requests\UpdateContratRequest;
use Illuminate\Validation\ValidationException;

class ContratController extends Controller
{
    public function index()
    {
        // Récupérer tous les contrats avec les historiques associés
        $contrats = Contrat::with('HistoriqueContrats')->get();

        // Retourner les contrats sous forme de réponse JSON
        return response()->json([
            'message' => 'Liste des contrats récupérée avec succès.',
            'data' => $contrats
        ], 200);
    }

    public function store(Request $request)
{
    try {
        // Validation des données
        $validatedData = $request->validate([
            'etat' => 'required|in:en_cours,terminer,annuler',
            'date' => 'required|date',
            'objectif' => 'required|string',
            'mode_paiement' => 'required|string',
            'nature_paiement' => 'required|string',
            'quantite' => 'required|integer',
            'presvu' => 'required|string',
            'force_majeure' => 'required|string',
            'projet_id' => 'required|exists:projets,id',
            'ressource_id' => 'required|exists:ressources,id',
            'user_id' => 'required|array',
            'user_id.*' => 'exists:users,id',
        ]);

        // Créer le contrat sans user_id
        $dataToStore = $validatedData;
        unset($dataToStore['user_id']); // Retirer user_id des données à enregistrer

        // Création du contrat
        $contrat = Contrat::create($dataToStore);

        // Attacher les utilisateurs au contrat via la table pivot
        $contrat->users()->attach($validatedData['user_id']);

        // Enregistrement dans l'historique
        HistoriqueContrat::create([
            'date' => $contrat->date,
            'etat' => $contrat->etat,
            'objectif' => $contrat->objectif,
            'mode_paiement' => $contrat->mode_paiement,
            'nature_paiement' => $contrat->nature_paiement,
            'quantite' => $contrat->quantite,
            'presvu' => $contrat->presvu, // Corrigé ici
            'force_majeure' => $contrat->force_majeure,
            'projet_id' => $contrat->projet_id,
            'contrat_id' => $contrat->id,
        ]);

        return response()->json([
            'message' => 'Contrat créé avec succès.',
            'data' => $contrat,
            'users' => $contrat->users // Affichage des utilisateurs associés
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Une erreur s\'est produite lors de la création du contrat.',
            'details' => $e->getMessage() // Retourne le message d'erreur
        ], 500);
    }
}


    
public function update(Request $request, $id)
{
    try {
        // Validation des données
        $validatedData = $request->validate([
            'etat' => 'required|in:en_cours,terminer,annuler',
            'date' => 'required|date',
            'objectif' => 'required|string',
            'mode_paiement' => 'required|string',
            'nature_paiement' => 'required|string',
            'quantite' => 'required|integer',
            'presvu' => 'required|string',
            'force_majeure' => 'required|string',
            'projet_id' => 'required|exists:projets,id',
            'ressource_id' => 'required|exists:ressources,id',
            'user_id' => 'required|array',
            'user_id.*' => 'exists:users,id',
        ]);

        // Trouver le contrat à mettre à jour
        $contrat = Contrat::findOrFail($id);

        // Préparer les données à mettre à jour, sans 'user_id'
        $updateData = [
            'etat' => $validatedData['etat'],
            'date' => $validatedData['date'],
            'objectif' => $validatedData['objectif'],
            'mode_paiement' => $validatedData['mode_paiement'],
            'nature_paiement' => $validatedData['nature_paiement'],
            'quantite' => $validatedData['quantite'],
            'presvu' => $validatedData['presvu'], // Vérifiez que c'est le bon nom de colonne
            'force_majeure' => $validatedData['force_majeure'],
            'projet_id' => $validatedData['projet_id'],
            'ressource_id' => $validatedData['ressource_id'],
        ];

        // Mise à jour des données du contrat
        $contrat->update($updateData);

        // Mise à jour des utilisateurs associés via la table pivot
        $contrat->users()->sync($validatedData['user_id']); // Utilisez sync pour mettre à jour les utilisateurs

        // Enregistrement dans l'historique
        HistoriqueContrat::create([
            'date' => $contrat->date,
            'etat' => $contrat->etat,
            'objectif' => $contrat->objectif,
            'mode_paiement' => $contrat->mode_paiement,
            'nature_paiement' => $contrat->nature_paiement,
            'quantite' => $contrat->quantite,
            'presvu' => $contrat->presvu, // Utilisez 'presvu' ici
            'force_majeure' => $contrat->force_majeure,
            'projet_id' => $contrat->projet_id,
            'contrat_id' => $contrat->id,
        ]);

        return response()->json([
            'message' => 'Contrat mis à jour avec succès.',
            'data' => $contrat,
            'users' => $contrat->users // Affichage des utilisateurs associés
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Une erreur s\'est produite lors de la mise à jour du contrat.',
            'details' => $e->getMessage() // Retourne le message d'erreur
        ], 500);
    }
}





    // Liste des historiques pour un contrat donné
    public function getHistorique($contrat_id)
    {
        $contrat = Contrat::find($contrat_id);

        if (!$contrat) {
            return response()->json([
                'error' => 'Contrat non trouvé.'
            ], 404);
        }

        $historiques = HistoriqueContrat::where('contrat_id', $contrat_id)->get();

        if ($historiques->isEmpty()) {
            return response()->json([
                'message' => 'Aucun historique trouvé pour ce contrat.',
            ], 200);
        }

        return response()->json([
            'message' => 'Historiques récupérés avec succès.',
            'data' => $historiques
        ], 200);
    }

    // Détails d'un contrat avec son historique
  // Détails d'un contrat avec son historique, ses personnes, ressources et projet
public function show($id)
{
    $contrat = Contrat::with(['historiqueContrats', 'users', 'ressource', 'projet'])->find($id);

    if (!$contrat) {
        return response()->json([
            'error' => 'Contrat non trouvé.'
        ], 404);
    }

    return response()->json([
        'message' => 'Détails du contrat récupérés avec succès.',
        'data' => $contrat
    ], 200);
}


      // Supprimer un contrat
      public function destroy($id)
{
    $contrat = Contrat::find($id);

    if (!$contrat) {
        return response()->json([
            'error' => 'Contrat non trouvé.'
        ], 404);
    }

    // Supprimer les historiques associés
    $contrat->historiqueContrats()->delete();

    // Suppression du contrat
    $contrat->delete();

    return response()->json([
        'message' => 'Contrat supprimé avec succès.'
    ], 200);
}


      public function showHistorique($id)
      {
          $historique = HistoriqueContrat::find($id);
  
          if (!$historique) {
              return response()->json(['error' => 'Historique non trouvé.'], 404);
          }
  
          return response()->json($historique);
      }
      
}
