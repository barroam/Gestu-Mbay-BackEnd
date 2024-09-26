<?php

namespace App\Http\Controllers;


use Log;
use App\Models\Engrais;
use App\Models\Semence;
use App\Models\Ressource;
use App\Models\Equipement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRessourceRequest;
use App\Http\Requests\UpdateRessourceRequest;

class RessourceController extends Controller
{
   // Afficher toutes les ressources
   public function index()
   {
       $ressources = Ressource::with(['semences', 'engrais', 'equipements'])->get();
       return response()->json($ressources);
   }


   // Ajouter une nouvelle ressource
   public function store(Request $request)
{
    $ressource = Ressource::create();

    // Ajout des semences
    foreach ($request->semences as $semence) {
        $ressource->semences()->attach($semence['id'], [
            'variete' => $semence['variete'],
            'quantite' => $semence['quantite']
        ]);
    }

    // Ajout des engrais
    foreach ($request->engrais as $engrais) {
        $ressource->engrais()->attach($engrais['id'], [
            'variete' => $engrais['variete'],
            'quantite' => $engrais['quantite']
        ]);
    }

    // Ajout des équipements (sans quantite)
    foreach ($request->equipements as $equipement) {
        $ressource->equipements()->attach($equipement['id']); // Pas de quantite ici
    }

    return response()->json(['message' => 'Ressource ajoutée avec succès !']);
}

   
public function show($id)
{
    // Récupérer la ressource par son ID
    $ressource = Ressource::with(['semences', 'engrais', 'equipements'])->find($id);

    // Vérifier si la ressource existe
    if (!$ressource) {
        return response()->json(['message' => 'Ressource non trouvée'], 404);
    }

    // Formater la réponse
    $data = [
        'id' => $ressource->id,
        'semences' => $ressource->semences()->withPivot('variete', 'quantite')->get(),
        'engrais' => $ressource->engrais()->withPivot('variete', 'quantite')->get(),
        'equipements' => $ressource->equipements()->get(),
    ];

    return response()->json($data);
}

public function update(Request $request, $id)
{
    $ressource = Ressource::findOrFail($id);
    $changed = false;

    // Vérifiez si les semences sont présentes et de type array
    if (!empty($request->semences) && is_array($request->semences)) {
        foreach ($request->semences as $semence) {
            if (!isset($semence['id'], $semence['variete'], $semence['quantite'])) {
                continue; // Ignorer si l'un des champs est manquant
            }

            $currentSemence = $ressource->semences()->find($semence['id']);
            if ($currentSemence) {
                if ($currentSemence->pivot->variete !== $semence['variete'] || 
                    $currentSemence->pivot->quantite !== $semence['quantite']) {
                    $ressource->semences()->syncWithoutDetaching([$semence['id'] => [
                        'variete' => $semence['variete'],
                        'quantite' => $semence['quantite']
                    ]]);
                    $changed = true;
                }
            } else {
                $ressource->semences()->attach($semence['id'], [
                    'variete' => $semence['variete'],
                    'quantite' => $semence['quantite']
                ]);
                $changed = true;
            }
        }
    }

    // Vérifiez si les engrais sont présents et de type array
    if (!empty($request->engrais) && is_array($request->engrais)) {
        foreach ($request->engrais as $engrais) {
            if (!isset($engrais['id'], $engrais['variete'], $engrais['quantite'])) {
                continue; // Ignorer si l'un des champs est manquant
            }

            $currentEngrais = $ressource->engrais()->find($engrais['id']);
            if ($currentEngrais) {
                if ($currentEngrais->pivot->variete !== $engrais['variete'] || 
                    $currentEngrais->pivot->quantite !== $engrais['quantite']) {
                    $ressource->engrais()->syncWithoutDetaching([$engrais['id'] => [
                        'variete' => $engrais['variete'],
                        'quantite' => $engrais['quantite']
                    ]]);
                    $changed = true;
                }
            } else {
                $ressource->engrais()->attach($engrais['id'], [
                    'variete' => $engrais['variete'],
                    'quantite' => $engrais['quantite']
                ]);
                $changed = true;
            }
        }
    }

    // Vérifiez si les équipements sont présents et de type array
    if (!empty($request->equipements) && is_array($request->equipements)) {
        foreach ($request->equipements as $equipement) {
            if (!isset($equipement['id'])) {
                continue; // Ignorer si l'ID de l'équipement est manquant
            }

            if (!$ressource->equipements()->find($equipement['id'])) {
                $ressource->equipements()->attach($equipement['id']);
                $changed = true;
            }
        }
    }

    if ($changed) {
        return response()->json(['message' => 'Ressource mise à jour avec succès !'], 200);
    } else {
        return response()->noContent(); // Retourne 204 No Content si aucun changement
    }
}

   
   
   

   // Supprimer une ressource
   public function destroy($id)
   {
       // Récupérer la ressource par ID
       $ressource = Ressource::find($id);
       // Vérifier si la ressource existe
       if (!$ressource) {
           return response()->json(['message' => 'Ressource non trouvée'], 404);
       }
   
       // Supprimer les relations avec les semences, engrais, et équipements
       $ressource->semences()->detach();
       $ressource->engrais()->detach();
       $ressource->equipements()->detach();
   
       // Supprimer la ressource elle-même
       $ressource->delete();
   
       return response()->json(['message' => 'Ressource supprimée avec succès !']);
   }
   

}
