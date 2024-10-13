<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjetController;
use App\Http\Controllers\ContratController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\EngraisController;
use App\Http\Controllers\SemenceController;
use App\Http\Controllers\RessourceController;
use App\Http\Controllers\AvisProjetController;
use App\Http\Controllers\EquipementController;
use App\Http\Controllers\InfoDemandeController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\ControleDemandeController;
use App\Http\Controllers\ApprobationContratController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//AUTHENTIFICATION
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    
});


// ASSIGNER PERMISSIONM DE ROA
Route::post('/assign-permissions-to-roa', [RolePermissionController::class, 'assignPermissionsToROA']);

Route::group(['middleware' => ['auth:api']], function () {
    // Route pour mettre à jour le profil de l'utilisateur
    Route::put('/update-profile', [UserController::class, 'updateProfile'])->name('update-profile');


// Afficher le contrat 
Route::get('/user/{id}/contrat', [UserController::class, 'getUserWithContrat']);

    // Route pour afficher les informations de l'utilisateur connecté
    Route::post('/me', [AuthController::class, 'me'])->name('me');
});

Route::group(['middleware' => ['auth:api', ]], function () {
   Route::get('/getcontrat/{id}',[UserController::class,'getcontrat']);
    // Route pour afficher les utilisateurs par rôle
    Route::get('/users/role/{roleName}', [UserController::class, 'getUsersByRole']);
    // Route pour afficher un utilisateur par rôle et ID
    Route::get('/users/role/{role}/user/{id}', [UserController::class, 'getUserByRoleAndId']);
    // Route pour afficher les rôles avec des utilisateurs uniques
    Route::get('/roles/users', [UserController::class, 'getRolesWithUniqueUsers']);
    // Route pour supprimer un utilisateur
    Route::delete('/user/delete/{id}', [UserController::class, 'deleteUser']);
  //Route pour la gestions des ressources
    Route::apiResource('ressources', RessourceController::class);
      //Route pour la gestions des semences
    Route::apiResource('semences', SemenceController::class);
      //Route pour la gestions des engrais
    Route::apiResource('engrais', EngraisController::class);
      //Route pour la gestions des equipements
    Route::apiResource('equipements', EquipementController::class);
      //Route pour la gestions desw controles d'éligibilté de la demande
    Route::apiResource('controle-demandes', ControleDemandeController::class);
    
    // Route pour la gestions des informations du demandeurs
    Route::apiResource('info-demandes', InfoDemandeController::class);

    Route::patch('/demandes/{demande}/status', [DemandeController::class, 'updateStatus']);

    //Route pour la gestion des demandes 
    Route::apiResource('demandes', DemandeController::class);


    //Route pour la gestions d'un profile
    Route::apiResource('projets', ProjetController::class);

   //Route pour afficher les historiques par projet
    Route::get('projets/{projet_id}/historiques', [ProjetController::class, 'showHistoriques']);
    
   // afficher les détails projets
    Route::get('historiques/{historique_id}', [ProjetController::class, 'showHistoriqueProjet']);
    
    // gestions des avis d'un projet 
    Route::apiResource('avis', AvisProjetController::class);

     // gestions des contrats 
    Route::apiResource('contrats', ContratController::class);

    // Route pour récupérer l'historique d'un contrat
    Route::get('contrats/{id}/historiques', [ContratController::class, 'getHistorique']); 

    // Route spécifique pour afficher un historique par ID
    Route::get('historiques/contrat/{id}', [ContratController::class, 'showHistorique']);

    //Route pour la gestions des apprbations 
    Route::apiResource('approbations', ApprobationContratController::class);
});

