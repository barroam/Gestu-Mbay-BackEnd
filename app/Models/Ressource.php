<?php

namespace App\Models;

use App\Models\Contrat;
use App\Models\Demande;
use App\Models\Engrais;
use App\Models\Semence;
use App\Models\Equipement;
use App\Models\Rendezvous;
use App\Models\EngraisRessource;
use App\Models\SemenceRessource;
use App\Models\EquipementRessource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ressource extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function semences()
    {
        return $this->belongsToMany(Semence::class, 'semence_ressources')
                    ->withPivot('variete', 'quantite'); // Ajoutez les colonnes de la table pivot si nécessaire
    }

    // Relation avec les engrais via la table pivot 'engrais_ressources'
    public function engrais()
    {
        return $this->belongsToMany(Engrais::class, 'engrais_ressources')
                    ->withPivot('variete', 'quantite'); // Ajoutez les colonnes de la table pivot si nécessaire
    }

    // Relation avec les équipements via la table pivot 'equipement_ressources'
    public function equipements()
    {
        return $this->belongsToMany(Equipement::class, 'equipement_ressources');
    }
}
