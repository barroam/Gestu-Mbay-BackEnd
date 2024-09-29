<?php

namespace App\Http\Controllers;


use ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\ApprobationContrat;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreApprobationContratRequest;
use App\Http\Requests\UpdateApprobationContratRequest;

class ApprobationContratController extends Controller
{
     // Récupérer toutes les approbations
     public function index()
     {
         try {
             $approbations = ApprobationContrat::all();
             return response()->json(['message' => 'Approbations récupérées avec succès.', 'data' => $approbations], 200);
         } catch (\Exception $e) {
             return response()->json(['error' => 'Erreur lors de la récupération des approbations: ' . $e->getMessage()], 500);
         }
     }
 


     
     // Afficher une approbation spécifique
     public function show($id)
     {
         try {
             $approbation = ApprobationContrat::findOrFail($id);
             return response()->json(['message' => 'Approbation récupérée avec succès.', 'data' => $approbation], 200);
         } catch (ModelNotFoundException $e) {
             return response()->json(['error' => 'Approbation non trouvée.'], 404);
         } catch (\Exception $e) {
             return response()->json(['error' => 'Erreur lors de la récupération de l\'approbation: ' . $e->getMessage()], 500);
         }
     }
 
     // Mettre à jour une approbation
     public function store(Request $request)
{
    // Validation des données d'entrée
    $validator = Validator::make($request->all(), [
        'contrat_id' => 'required|exists:contrats,id', // Contrat doit exister
        'approuve' => 'required|boolean', // Doit être un booléen
        'description' => 'required|string', // Description est requise
        'user_id' => 'required|exists:users,id', // L'utilisateur doit exister
    ]);

    // Vérification d'unicité pour l'approbation par utilisateur
    $validator->after(function ($validator) use ($request) {
        if (ApprobationContrat::where('contrat_id', $request->contrat_id)
            ->where('user_id', $request->user_id)
            ->exists()) {
            $validator->errors()->add('user_id', 'Cet utilisateur a déjà approuvé ce contrat.');
        }
    });

    // Vérification si le contrat a déjà deux approbations
    $approbationCount = ApprobationContrat::where('contrat_id', $request->contrat_id)->count();
    if ($approbationCount >= 2) {
        return response()->json(['error' => 'Le contrat a déjà été approuvé par deux utilisateurs.'], 403);
    }

    // Si la validation échoue, retourner les erreurs
    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 422);
    }

    try {
        // Création d'une nouvelle approbation
        $approbation = ApprobationContrat::create($request->all());

        // Récupérer les utilisateurs associés aux approbations
        $users = ApprobationContrat::where('contrat_id', $approbation->contrat_id)
            ->with('user') // Assurez-vous d'avoir défini la relation `user` dans votre modèle
            ->get()
            ->pluck('user'); // Récupérer uniquement les utilisateurs

        return response()->json([
            'message' => 'Approbation créée avec succès.',
            'data' => $approbation,
            'users' => $users,
        ], 201);
    } catch (\Exception $e) {
        // Gestion des exceptions en cas d'erreur
        return response()->json(['error' => 'Erreur lors de la création de l\'approbation: ' . $e->getMessage()], 500);
    }
}

//Modification
public function update(Request $request, $id)
{
    // Validation des données d'entrée
    $validator = Validator::make($request->all(), [
        'approuve' => 'required|boolean', // Doit être un booléen
        'description' => 'required|string', // Description est requise
        'user_id' => 'required|exists:users,id', // L'utilisateur doit exister
    ]);

    // Vérification d'unicité pour l'approbation par utilisateur
    $validator->after(function ($validator) use ($request, $id) {
        // Récupérer l'approbation actuelle
        $approbation = ApprobationContrat::find($id);
        
        // Vérifier si l'utilisateur essaie d'approuver à nouveau le même contrat
        if ($approbation && $approbation->user_id !== $request->user_id) {
            if (ApprobationContrat::where('contrat_id', $approbation->contrat_id)
                ->where('user_id', $request->user_id)
                ->exists()) {
                $validator->errors()->add('user_id', 'Cet utilisateur a déjà approuvé ce contrat.');
            }
        }
    });

    // Si la validation échoue, retourner les erreurs
    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 422);
    }

    try {
        // Récupérer l'approbation par ID
        $approbation = ApprobationContrat::findOrFail($id);

        // Mettre à jour l'approbation
        $approbation->update($request->all());

        return response()->json(['message' => 'Approbation mise à jour avec succès.', 'data' => $approbation], 200);
    } catch (\Exception $e) {
        // Gestion des exceptions en cas d'erreur
        return response()->json(['error' => 'Erreur lors de la mise à jour de l\'approbation: ' . $e->getMessage()], 500);
    }
}

 
public function destroy($id)
{
    try {
        // Récupérer l'approbation par ID
        $approbation = ApprobationContrat::findOrFail($id);

        // Récupérer le contrat associé pour vérifier les approbations restantes
        $contrat_id = $approbation->contrat_id;

        // Supprimer l'approbation
        $approbation->delete();

        // Récupérer les utilisateurs restants qui ont approuvé le contrat
        $users = ApprobationContrat::where('contrat_id', $contrat_id)
            ->with('user') // Assurez-vous d'avoir défini la relation `user` dans votre modèle
            ->get()
            ->pluck('user'); // Récupérer uniquement les utilisateurs

        return response()->json([
            'message' => 'Approbation supprimée avec succès.',
            'remaining_users' => $users,
        ], 200);
    } catch (\Exception $e) {
        // Gestion des exceptions en cas d'erreur
        return response()->json(['error' => 'Erreur lors de la suppression de l\'approbation: ' . $e->getMessage()], 500);
    }
}

}
