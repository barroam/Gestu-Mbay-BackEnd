<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Semence;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSemenceRequest;
use App\Http\Requests\UpdateSemenceRequest;

class SemenceController extends Controller
{
   // Afficher toutes les semences
   public function index()
   {
       $semences = Semence::all();
       return response()->json($semences);
   }

   public function store(Request $request)
   {
       // Validation des données d'entrée
       $validated = $request->validate([
           'nom' => 'required|string|max:255',
           'image' => 'required|url',  // Le champ image est un lien
       ]);
   
       try {
           // Création de la nouvelle semence
           $semence = Semence::create($validated);
   
           if ($semence) {
               return response()->json([
                   'message' => 'Semence ajoutée avec succès.',
                   'semence' => $semence
               ], 201);
           } else {
               return response()->json(['error' => 'La semence n\'a pas pu être ajoutée.'], 500);
           }
       } catch (\Exception $e) {
           return response()->json([
               'error' => 'Erreur lors de l\'ajout de la semence.',
               'details' => $e->getMessage()
           ], 500);
       }
   }
   
   
   

   // Afficher une semence spécifique
   public function show($id)
   {
       try {
           $semence = Semence::findOrFail($id);
           return response()->json($semence);
       } catch (Exception $e) {
           return response()->json(['error' => 'Semence non trouvée : ' . $e->getMessage()], 404);
       }
   }

   public function update(Request $request, $id)
   {
       // Valider les données entrantes
       $validated = $request->validate([
           'nom' => 'sometimes|required|string|max:255',
           'image' => 'sometimes|url',  // Le champ image est un lien
       ]);
   
       try {
           // Rechercher la semence à mettre à jour
           $semence = Semence::findOrFail($id);
   
           // Mettre à jour les champs validés
           $semence->update($validated);
   
           return response()->json([
               'message' => 'Semence mise à jour avec succès.',
               'semence' => $semence
           ], 200);
       } catch (\Exception $e) {
           return response()->json([
               'error' => 'Erreur lors de la mise à jour de la semence.',
               'details' => $e->getMessage()
           ], 500);
       }
   }
   

   // Supprimer une semence
   public function destroy($id)
   {
       try {
           $semence = Semence::findOrFail($id);
           $semence->delete();
           return response()->json(['message' => 'Semence supprimée avec succès']);
       } catch (\Exception $e) {
           return response()->json(['error' => 'Erreur lors de la suppression de la semence : ' . $e->getMessage()], 500);
       }
}
}