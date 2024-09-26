<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    //
    public function assignPermissionsToROA()
    {
        // Rechercher l'utilisateur avec le rôle "ROA"
        $user = User::whereHas('roles', function($q){
            $q->where('name', 'ROA');
        })->first(); // ou utiliser find(id) pour un utilisateur spécifique

        if ($user) {
            // Récupérer toutes les permissions sauf "manage users"
            $permissions = Permission::where('name', '!=', 'manage users')->get();

            // Assigner ces permissions à l'utilisateur
            $user->syncPermissions($permissions);

            return response()->json([
                'success' => true,
                'message' => 'Les permissions ont été assignées avec succès au ROA.',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur ROA non trouvé.',
            ]);
        }
    }
}
