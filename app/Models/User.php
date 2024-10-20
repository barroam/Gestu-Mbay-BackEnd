<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Role;
use App\Models\Projet;
use App\Models\Demande;
use App\Models\AvisProjet;

use App\Models\Rendezvous;
use App\Models\ApprobationContrat;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
  
     public function contrats(): BelongsToMany
     {
         return $this->belongsToMany(Contrat::class, 'contrat_user', 'user_id', 'contrat_id')
                     ->withTimestamps();  // pour gérer les dates de création/mise à jour
     }
     
    public function projets()
    {
        return $this->hasOne(Projet::class);
    }
    public function approbationContrats()
    {
        return $this->hasMany(ApprobationContrat::class);
    }
    public function avis()
    {
        return $this->hasMany(AvisProjet::class);
    }
    public function Rendezvous()
    {
        return $this->belongsToMany(Rendezvous::class);
    }
    public function Demande(){
    return $this->hasOne(Demande::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
 
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

}
