<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Contrat;
use Illuminate\Http\Request;



use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
  
    public function deleteUser($id)
{
    // Vérifier si l'utilisateur est un administrateur
    if (!auth()->user()->hasRole('admin')) {
        return response()->json([
            'success' => false,
            'message' => 'Accès refusé. Seuls les administrateurs peuvent supprimer des utilisateurs.',
        ], 403);
    }

    // Rechercher l'utilisateur par son ID
    $user = User::find($id);

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Utilisateur non trouvé.',
        ], 404);
    }

    // Supprimer l'utilisateur
    $user->delete();

    return response()->json([
        'success' => true,
        'message' => 'Utilisateur supprimé avec succès.',
    ], 200);
}
public function updateProfile(Request $request)
{
    $user = auth()->user();

    $validator = Validator::make($request->all(), [
        'name' => 'sometimes|required|string|max:255',
        'email' => 'sometimes|required|email|unique:users,email,'.$user->id.'|max:255',
        'password' => 'sometimes|nullable|confirmed|min:8',
        'role' => 'sometimes|required|in:admin,agriculteur,fournisseur,ROA', // Validation pour le rôle
    ], [
        'name.required' => 'Le champ nom est obligatoire.',
        'email.required' => 'Le champ email est obligatoire.',
        'email.email' => 'L\'email doit être une adresse email valide.',
        'email.unique' => 'Cet email est déjà utilisé.',
        'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        'role.required' => 'Le champ rôle est obligatoire.',
        'role.in' => 'Le rôle doit être un des suivants : admin, agriculteur, fournisseur, ROA.',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors(),
        ], 400);
    }

    $user->name = $request->name ?? $user->name;
    $user->email = $request->email ?? $user->email;

    if ($request->filled('password')) {
        $user->password = bcrypt($request->password);
    }

    // Gestion du rôle
    if ($request->filled('role')) {
        // Détacher tous les rôles existants
        $user->roles()->detach();

        // Assigner le nouveau rôle
        $user->assignRole($request->role);

        // Gérer les permissions
        if ($user->hasRole('admin')) {
            // Récupérer toutes les permissions
            $permissions = Permission::all();
            $user->givePermissionTo($permissions);
        } elseif ($user->hasRole('ROA')) {
            // Récupérer toutes les permissions sauf "manage users"
            $permissions = Permission::where('name', '!=', 'manage users')->get();
            $user->givePermissionTo($permissions);
        } else {
            // Si ce n'est pas un admin ou ROA, détacher toutes les permissions
            $user->syncPermissions([]);
        }
    }

    $user->save(); // N'oubliez pas d'enregistrer les modifications

    // Récupération des informations de l'utilisateur avec le rôle
    $userData = [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $user->getRoleNames(),
        'created_at' => $user->created_at,
        'updated_at' => $user->updated_at,
    ];

    return response()->json([
        'success' => true,
        'data' => $userData,
    ], 200);
}

 // Afficher les utilisateurs par rôle


 public function getUsersByRole($roleName)
 {
     // Requête pour récupérer uniquement les utilisateurs qui ont le rôle spécifié
     $users = User::whereHas('roles', function ($query) use ($roleName) {
         $query->where('name', $roleName);
     })->get();
 
     if ($users->isEmpty()) {
         return response()->json([
             'success' => false,
             'message' => "Aucun utilisateur trouvé pour le rôle : $roleName.",
         ], 404);
     }
 
     $usersData = $users->map(function ($user) {
         return [
             'id' => $user->id,
             'name' => $user->name,
             'email' => $user->email,
             'created_at' => $user->created_at,
             'updated_at' => $user->updated_at,
         ];
     });
 
     return response()->json([
         'success' => true,
         'role' => $roleName,
         'users' => $usersData,
     ], 200);
 }
 
 public function getUserWithContrat($id)
 {
     // Récupérer l'utilisateur avec son contrat
     $user = User::with('contrats')->find($id);

     if (!$user) {
         return response()->json(['message' => 'Utilisateur non trouvé'], 404);
     }

     return response()->json($user);
 }
}