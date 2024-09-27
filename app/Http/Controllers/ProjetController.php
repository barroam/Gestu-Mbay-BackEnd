<?php

namespace App\Http\Controllers;

use App\Models\Projet;
use Illuminate\Http\Request;
use App\Models\HistoriqueProjet;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreProjetRequest;
use App\Http\Requests\UpdateProjetRequest;

class ProjetController extends Controller
{
     // Affiche tous les projets
     public function index()
     {
         $projets = Projet::all();
 
         if ($projets->isEmpty()) {
             return response()->json(['message' => 'Aucun projet trouvé.'], 404);
         }
 
         return response()->json([
             'message' => 'Projets récupérés avec succès.',
             'data' => $projets
         ], 200);
     }
 
     // Crée un nouveau projet
     public function store(Request $request)
     {
         $validated = $request->validate([
             'etat' => 'required|in:en_cours,terminer,annuler',
             'type_activite' => 'required|string|max:255',
             'date' => 'required|date',
             'attentes' => 'required|string',
             'obstacles' => 'required|string',
             'solutions' => 'required|string',
             'date_fin' => 'nullable|date',
         ]);
     
         try {
             // Démarrer une transaction pour s'assurer que tout est atomique
             DB::beginTransaction();
     
             // Création du projet
             $projet = Projet::create($validated);
     
             if ($projet) {
                 // Ajouter l'historique seulement si le projet est créé avec succès
                 $projet->addHistorique();
     
                 // Confirmer la transaction
                 DB::commit();
     
                 return response()->json([
                     'message' => 'Projet créé avec succès, historique ajouté.',
                     'data' => $projet
                 ], 201);
             } else {
                 // Si le projet n'a pas pu être créé, retourner une erreur
                 DB::rollBack();
                 return response()->json([
                     'error' => 'Échec de la création du projet.'
                 ], 400);
             }
         } catch (\Exception $e) {
             // Annuler la transaction en cas d'erreur
             DB::rollBack();
     
             return response()->json([
                 'error' => 'Erreur lors de la création du projet.',
                 'details' => $e->getMessage()
             ], 500);
         }
     }
     
 
     // Affiche un projet spécifique
     public function show($id)
     {
         // Recherche du projet
         $projet = Projet::find($id);
 
         if (!$projet) {
             return response()->json([
                 'message' => 'Projet non trouvé.',
                 'details' => 'Aucun projet avec l\'ID spécifié n\'a été trouvé.'
             ], 404);
         }
 
         return response()->json([
             'message' => 'Projet récupéré avec succès.',
             'data' => $projet
         ], 200);
     }
 
     public function update(Request $request, Projet $projet)
     {
         $validated = $request->validate([
             'etat' => 'required|in:en_cours,terminer,annuler',
             'type_activite' => 'required|string|max:255',
             'date' => 'required|date',
             'attentes' => 'required|string',
             'obstacles' => 'required|string',
             'solutions' => 'required|string',
             'date_fin' => 'nullable|date',
         ]);
     
         try {
             // Démarrer une transaction pour garantir l'intégrité des données
             DB::beginTransaction();
     
             // Mise à jour du projet
             if ($projet->update($validated)) {
                 // Si la mise à jour est réussie, ajouter un nouvel historique
                 $projet->addHistorique();
     
                 // Confirmer la transaction
                 DB::commit();
     
                 return response()->json([
                     'message' => 'Projet mis à jour avec succès, historique ajouté.',
                     'data' => $projet
                 ], 200);
             } else {
                 // Si la mise à jour échoue, retourner une erreur
                 DB::rollBack();
                 return response()->json([
                     'error' => 'Échec de la mise à jour du projet.'
                 ], 400);
             }
         } catch (\Exception $e) {
             // Annuler la transaction en cas d'erreur
             DB::rollBack();
     
             return response()->json([
                 'error' => 'Erreur lors de la mise à jour du projet.',
                 'details' => $e->getMessage()
             ], 500);
         }
     }
     
 
     // Supprime un projet spécifique
     public function destroy($id)
     {
         // Recherche du projet
         $projet = Projet::find($id);
 
         if (!$projet) {
             return response()->json([
                 'message' => 'Projet non trouvé.',
                 'details' => 'Aucun projet avec l\'ID spécifié n\'a été trouvé.'
             ], 404);
         }
 
         // Suppression du projet
         $projet->delete();
 
         return response()->json([
             'message' => 'Projet supprimé avec succès.'
         ], 200);
     }


     //DETAILS PROJET
     public function showHistoriques($projet_id)
{
    // Vérifier si le projet existe
    $projet = Projet::find($projet_id);

    if (!$projet) {
        return response()->json([
            'error' => 'Projet non trouvé.'
        ], 404);
    }

    // Récupérer les historiques liés à ce projet
    $historiques = $projet->historiques;

    if ($historiques->isEmpty()) {
        return response()->json([
            'message' => 'Aucun historique trouvé pour ce projet.',
        ], 200);
    }

    return response()->json([
        'message' => 'Historiques récupérés avec succès.',
        'data' => $historiques
    ], 200);
}

//LISTE HISTOIQUES PROJET PAR PROJET
public function showHistoriqueProjet($historiqueId)
{
    $historique = HistoriqueProjet::find($historiqueId);

    if (!$historique) {
        return response()->json([
            'error' => 'Historique non trouvé.',
        ], 404);
    }

    return response()->json([
        'message' => 'Historique récupéré avec succès.',
        'data' => $historique
    ], 200);
}

}
