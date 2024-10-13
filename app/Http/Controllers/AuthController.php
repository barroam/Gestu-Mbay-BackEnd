<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register() {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|confirmed|min:8',
            'role' => 'required|in:admin,agriculteur,fournisseur,ROA', // Validation pour le rôle
        ], [
            'name.required' => 'Le champ nom est obligatoire.',
            'name.string' => 'Le nom doit être une chaîne de caractères.',
            'name.max' => 'Le nom ne doit pas dépasser 255 caractères.',
            'email.required' => 'Le champ email est obligatoire.',
            'email.email' => 'L\'email doit être une adresse email valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'email.max' => 'L\'email ne doit pas dépasser 255 caractères.',
            'password.required' => 'Le champ mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'role.required' => 'Le champ rôle est obligatoire.',
            'role.in' => 'Le rôle doit être un des suivants : admin, agriculteur, fournisseur, ROA',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 400);
        }
    
        // Création de l'utilisateur
        $user = new User;
        $user->name = request()->name;
        $user->email = request()->email;
        $user->password = bcrypt(request()->password);
        $user->save();
    
        // Assignation du rôle à l'utilisateur
        $user->assignRole(request()->role);


            
        if ($user->hasRole('admin')) {
            // Récupérer toutes les permissions
            $permissions = Permission::all();

            // Assigner toutes les permissions à l'utilisateur
            $user->givePermissionTo($permissions);
        }
        if ($user->hasRole('ROA')) {
            // Récupérer toutes les permissions sauf "manage users"
            $permissions = Permission::where('name', '!=', 'manage users')->get();
        
            // Assigner ces permissions à l'utilisateur
            $user->givePermissionTo($permissions);
        }
    
        // Récupération des informations de l'utilisateur avec le rôle
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->getRoleNames(), // Cela renvoie les noms des rôles sous forme de collection
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    
        return response()->json([
            'success' => true,
            'data' => $userData, // Inclure les informations de l'utilisateur
        ], 201);
    }
    
    
  
  
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
    
        // Valider les informations d'identification
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Le champ email est obligatoire.',
            'email.email' => 'L\'email doit être une adresse email valide.',
            'password.required' => 'Le champ mot de passe est obligatoire.',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 400);
        }
    
        // Tenter de connecter l'utilisateur
        if (!$token = auth()->guard('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'error' => 'Identifiants invalides. Veuillez vérifier votre email et votre mot de passe.',
            ], 401);
        }
    
        // Récupérer l'utilisateur authentifié
        $user = auth()->guard('api')->user();
    
        // Préparer les données de l'utilisateur
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->getRoleNames(), // Récupérer les rôles de l'utilisateur
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    
      $permissions = $user->getAllPermissions();

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => $userData,
            'permissions' => $permissions, // Inclure les informations de l'utilisateur
        ], 200);
    }
    
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }
  
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
  
        return response()->json(['message' => 'Successfully logged out']);
    }
  
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
  
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
{
    return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth()->guard('api')->factory()->getTTL() * 120
    ]);
}

//



}
