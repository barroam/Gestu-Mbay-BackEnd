<?php

namespace App\Http\Controllers;

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

   // Ajouter une nouvelle semence
   public function store(Request $request)
   {
       $validated = $request->validate([
           'nom' => 'required|string|max:255',
           'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
       ]);

       try {
           $semence = Semence::create($validated);
           return response()->json($semence, 201);
       } catch (\Exception $e) {
           return response()->json(['error' => 'Erreur lors de l\'ajout de la semence : ' . $e->getMessage()], 500);
       }
   }

   // Afficher une semence spécifique
   public function show($id)
   {
       try {
           $semence = Semence::findOrFail($id);
           return response()->json($semence);
       } catch (\Exception $e) {
           return response()->json(['error' => 'Semence non trouvée : ' . $e->getMessage()], 404);
       }
   }

   // Mettre à jour une semence
   public function update(Request $request, $id)
   {
       $validated = $request->validate([
           'nom' => 'sometimes|required|string|max:255',
           'image' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
       ]);

       try {
           $semence = Semence::findOrFail($id);
           $semence->update($validated);
           return response()->json($semence);
       } catch (\Exception $e) {
           return response()->json(['error' => 'Erreur lors de la mise à jour de la semence : ' . $e->getMessage()], 500);
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