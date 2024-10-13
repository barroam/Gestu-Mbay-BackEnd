<?php

namespace App\Models;

use App\Models\User;
use App\Models\Projet;
use App\Models\HistoriqueContrat;
use App\Models\ApprobationContrat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Contrat extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'contrat_user', 'contrat_id', 'user_id')
                    ->withTimestamps();
    }

    public function projet()
    {
        return $this->belongsTo(Projet::class);
    }

    public function historiqueContrats()
    {
        return $this->hasMany(HistoriqueContrat::class);
    }

    public function approbationContrat()
    {
        return $this->hasOne(ApprobationContrat::class);
    }
    public function Ressource () {
        return $this->belongsTo(Ressource::class);
    } 
}
