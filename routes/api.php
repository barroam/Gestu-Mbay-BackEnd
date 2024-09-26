<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EngraisController;
use App\Http\Controllers\SemenceController;
use App\Http\Controllers\RessourceController;
use App\Http\Controllers\EquipementController;
use App\Http\Controllers\RolePermissionController;

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

Route::group(['middleware' => ['auth:api'], 'prefix' => 'profile'], function () {
    // Route pour mettre à jour le profil de l'utilisateur
    Route::put('/update-profile', [UserController::class, 'updateProfile'])->name('update-profile');
    // Route pour afficher les informations de l'utilisateur connecté
    Route::post('/me', [AuthController::class, 'me'])->name('me');
});

Route::group(['middleware' => ['auth:api', ]], function () {
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
});

