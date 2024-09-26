<?php

namespace App\Models;

use App\Models\Projet;
use App\Models\HistoriqueContrat;
use App\Models\ApprobationContrat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contrat extends Model
{
    use HasFactory;

    protected $guard= [];

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
